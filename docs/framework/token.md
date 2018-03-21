CiviCRM's [token functionality](https://docs.civicrm.org/user/en/latest/common-workflows/tokens-and-mail-merge/) originates in CiviMail, which focuses on writing and delivering newsletters to large constituencies. In its original form, the design placed heavy weight on:

- **Performance**: Divide mail-composition into batches and split the batches among parallel workers. Moreover, when processing each batch, improve efficiency by minimizing the #SQL queries - i.e. fetch all records in a batch at once, and only fetch columns which are actually used.
- **Security**: Do not trust email authors with a fully programmable language.
- **Contact Records**: The main data for mail-merge came from contact records. Other data (contribution, event, participant, membership, etc) were not applicable.
- **Adaptive Text/HTML**: Email messages often have two renditions, `text/plain` and `text/html`. Some tokens, such as `{domain.address}`, may present different formatting in each medium. Other tokens, such as `{action.unsubscribe}`, can even present a different user-experience.

Over time, the token functionality evolved:

- Add optional support for more powerful templates with conditions and loops (Smarty). (In the case of CiviMail, this was still disabled by default as a security consideration, but in other use-cases it might be enabled by default.)
- Add a hook for custom tokens.
- Expand to other applications, such as individual mailings, print letters, receipts for contributions, and scheduled reminders.

### Example Tokens

Some example tokens and their meaning

| Token | Value |
| --- | --- |
| `{domain.name}` | Name of this Domain |
| `{domain.address}` | Meta-token constructed by merging the various address components from `civicrm_domain` |
| `{domain.phone}` | Phone Number for this domain |
| `{domain.email}` | Primary email address to contact this domain |
| `{contact.display_name}` | The contact's `display_name` (also used in the To: header) |
| `{contact.xxx}` | the value of xxx as returned by a `contact.get` api call |
| `{action.forward}` | Link to forward this mailing to an unsubscribed user |
| `{action.donate}` | Link to make a donation |
| `{action.reply}` | mailto: link to reply |
| `{action.unsubscribe}` | mailto: link to unsubscribe |
| `{action.optOut}` | mailto: link to opt out of the domain |
| `{mailing.groups}` | The list of target groups for this mailing |
| `{mailing.name}` | The name of the mailing |
| `{mailing.name}` | The name of the mailing |
| `{unsubscribe.group}` | A bulleted list of groups from which the contact has been unsubscribed, along with web links to resubscribe. |

For more examples of tokens and token replacement see the [Token documentation](https://wiki.civicrm.org/confluence/display/CRMDOC/Tokens)

### Fixme

As the use-cases grew, techniques from the original CiviMail code were duplicated and adapted, leading to a lengthy idiom which looks a bit like this:

```php
$subject = $template->subject;
$body_html = $template->body_html;
$body_text = $template->body_text;

$subject = CRM_Utils_Token::replaceFooTokens($subject, $fooData... $encodingOptions...);
$subject = CRM_Utils_Token::replaceBarTokens($subject, $barData... $encodingOptions...);
$subject = CRM_Utils_Token::replaceHookTokens($subject, $encodingOptions...);
if (smarty enabled) $subject = $smarty->display("string:$subject");

$body_html = CRM_Utils_Token::replaceFooTokens($body_html, $fooData... $encodingOptions...);
$body_html = CRM_Utils_Token::replaceBarTokens($body_html, $barData... $encodingOptions...);
$body_html = CRM_Utils_Token::replaceHookTokens($body_html, $encodingOptions...);
if (smarty enabled) $body_html = $smarty->display("string:$body_html");

$body_text = CRM_Utils_Token::replaceFooTokens($body_text, $fooData... $encodingOptions...);
$body_text = CRM_Utils_Token::replaceBarTokens($body_text, $barData... $encodingOptions...);
$body_text = CRM_Utils_Token::replaceHookTokens($body_text, $encodingOptions...);
if (smarty enabled) $body_text = $smarty->display("string:$body_text");
```

Some of the key functions of this system are:

- `CRM_Utils_Token::getTokens` - Retrieves an array of tokens contained in the given string e.g. HTML of an email
- `CRM_Utils_Token::getRequiredTokens` - What are the minimum required tokens for CiviMail
- `CRM_Utils_Token::requiredTokens` - Check that the required tokens are there
- `CRM_Utils_Token::&replace<type>Tokens` - Replaces x type of Tokens where x is User, Contact, Action, Resubscribe etc
- `CRM_Utils_Token::get<type>TokenReplacement` - Format and escape for use in Smarty the found content for Tokens for x type. This is usually called within `CRM_Utils_Token::&replace<type>Tokens`


In 4.7+ there was major changes to the Scheduled Reminders facility which also included potential changes to CiviMail in so far as how tokens are generated see [CRM-13244](https://issues.civicrm.org/jira/browse/CRM-13244). There is a move to use more of the `Civi\Token\TokenProcessor` sub system as this is more robust. However there have been compatibility layers built in to use the older `CRM_Utils_Token` processors. Developers should aim to work off the `Civi\Token\TokenProcessor` where possible. However there are still some systems that haven't been refactored. Some of the key functions in the older systems are.

This new system of generating content for tokens has a number of advantages
- Decreases the number of SQL Queries
- Is not as tightly coupled with the one templating engine

The basic process in the new subsystem is
- Whenever an application's controller (e.g. for CiviMail or PDFs or scheduled reminders) needs to work with tokens, it instantiates `Civi\Token\TokenProcessor`.
- The `controller` passes some information to `TokenProcessor` – namely, the `$context` and the list of `$rows`.
- The `TokenProcessor` fires an event (`TOKEN_EVALUATE`). Other modules respond with the actual token content.
- For each of the rows, the controller requests a rendered blob of text.

```php
$p = new TokenProcessor(Container::singleton()->get('dispatcher'), array(
  'controller' => __CLASS__,
  'smarty' => FALSE,
));

// Fill the processor with a batch of data.
$p->addMessage('body_text', 'Hello {contact.display_name}!', 'text/plain');
$p->addRow()->context('contact_id', 123);
$p->addRow()->context('contact_id', 456);

// Lookup/compose any tokens which are referenced in the message.
// e.g. SELECT id, display_name FROM civicrm_contact WHERE id IN (...contextual contact ids...);
$p->evaluate();

// Display mail-merge data.
foreach ($p->getRows() as $row) {
  echo $row->render('body_text');
}
```

### Extending the Token system

In the old system the standard way extension authors would  extend the list of tokens by implement [hook_civicrm_tokens](/hooks/hook_civicrm_tokens.md). The content of the custom token needs to be set with [hook_civicrm_tokenValues](/hooks/hook_civicrm_tokenValues.md).

To utilise the newer method extension authors should implement code similar to the following. This is able to be done because when executing `TokenProcessor::evaluate()`, the processor dispatches an event so that other classes may define token content.

```php
function example_civicrm_container($container) {
  $container->addResource(new \Symfony\Component\Config\Resource\FileResource(__FILE__));
  $container->findDefinition('dispatcher')->addMethodCall('addListener',
    array(\Civi\Token\Events::TOKEN_REGISTER, 'example_register_tokens')
  );
  $container->findDefinition('dispatcher')->addMethodCall('addListener',
    array(\Civi\Token\Events::TOKEN_EVALUATE, 'example_evaluate_tokens')
  );
}

function example_register_tokens(\Civi\Token\Event\TokenRegisterEvent $e) {
  $e->entity('profile')
    ->register('viewUrl', ts('Profile View URL'))
    ->register('viewLink', ts('Profile View Link'));
}

function example_evaluate_tokens(\Civi\Token\Event\TokenValueEvent $e) {
  foreach ($e->getRows() as $row) {
    /** @var TokenRow $row */
    $row->format('text/html');
    $row->tokens('profile', 'viewUrl', 'http://example.com/profile/' . $row->context['contact_id']);
    $row->tokens('profile', 'viewLink', ts("<a href='%1'>Open Profile</a>", array(
      1 => $row->tokens['profile']['viewUrl'],
    )));
  }
}
```

Some notes on the the above

- `$row->context['...']` returns contextual data, regardless of whether you declared it at the row level or the processor level.
- To update a row's data, use the `context()` and `tokens()` methods. To read a row's data, use the $context and $tokens properties. These interfaces support several notations, which are described in the TokenRow class.
- You have control over the loop. You can do individual data-lookups in the loop (for simplicity) – or you can also do prefetches and batched lookups (for performance).
- The class `\Civi\Token\AbstractTokenSubscriber` provides a more structured/opinionated way to handle these events.
- For background on the `event dispatcher` (e.g. `listeners` vs subscribers), see [Symfony Documentation](http://symfony.com/doc/current/components/event_dispatcher/introduction.html)
