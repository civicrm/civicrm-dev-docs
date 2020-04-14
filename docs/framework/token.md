## Introduction

CiviCRM [tokens](https://docs.civicrm.org/user/en/latest/common-workflows/tokens-and-mail-merge/) enable one to generate personalized emails, reminders, print documents. etc. 

When working with tokens as a developer, there are two major tasks:

- *Composing a message* by evaluating a token expression (`Hello {contact.first_name}! How do you like {address.city}?`).
- *Defining a token* and its content.

For each task, there are a couple available patterns, and we'll explore them in more depth below. But first, it helps to put the functionality in context.

## Background

Token functionality originated in CiviMail, which focuses on writing and delivering newsletters to large constituencies. In its original form, the design placed heavy weight on:

- **Performance**: Divide mail-composition into batches and split the batches among parallel workers. Moreover, when processing each batch, improve efficiency by minimizing the #SQL queries - i.e. fetch all records in a batch at once, and only fetch columns which are actually used.
- **Security**: Do not trust email authors with a fully programmable language.
- **Contact Records**: The main data for mail-merge came from contact records. Other data (contribution, event, participant, membership, etc) were not applicable.
- **Adaptive Text/HTML**: Email messages often have two renditions, `text/plain` and `text/html`. Some tokens, such as `{domain.address}`, may present different formatting in each medium. Other tokens, such as `{action.unsubscribe}`, can even present a different user-experience.

Over time, the token functionality evolved:

- Add optional support for more powerful templates with conditions and loops (**Smarty**). (In the case of CiviMail, this was still disabled by default as a security consideration, but in other use-cases it might be enabled by default.)
- Add a **hook for custom tokens**.
- Expand to other applications, such as **individual mailings, print letters, receipts for contributions, and scheduled reminders**.

## Examples

| Token | Description |
| --- | --- |
| `{domain.name}` | Name of this domain (organization/site/deployment) |
| `{domain.address}` | Meta-token with the full-formed mailing address of this domain (organization/site/deployment)) |
| `{contact.first_name}`| The contact's `first_name` |
| `{contact.display_name}` | The contact's `display_name` |
| `{mailing.name}` | The name of the mailing |

For more examples of tokens and token replacement, see [User Guide: Common workflows: Tokens and mail merge](https://docs.civicrm.org/user/en/latest/common-workflows/tokens-and-mail-merge/).

## Composing messages

### CRM_Utils_Token {:#crm-utils-token}

For most of its history, CiviMail used a helper class, `CRM_Utils_Token`, with a number of static helper functions.  As the more kinds of tokens were created for more use-cases, the technique was duplicated and adapted, leading to an idiom which looks a bit like this:

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

However, this idiom has a few problems:

- Token substitution is performed *iteratively* and not *atomicly*.  To ensure secure and consistent handling of tokens, one has to be quite careful with the selection/ordering/encoding of each call to `replace<Type>Tokens()`.
- Token substitution is not standardized. To make any general improvement to the token language or process, one must work through disparate functions and use-cases.
- Encoding issues (regarding HTML and Smarty) are handled by each function separately, leading to inconsistencies.

Consequently, this pattern is not recommend for new code.

### TokenProcessor (v4.7+) {:#token-processor}

CiviCRM v4.7 introduced `Civi\Token\TokenProcessor`, which provides a more flexible way to define and process tokens.  It preserves the performance, batching, and security virtues of `CRM_Utils_Token` and also:

- Allows more *contextual* information -- enabling tokens for more entities.
- Loosens the coupling between token-consumers and token-providers.
- Loosens the coupling between token-content and template-language.

Originally, `TokenProcessor` was introduced to support extensible, contextual tokens in Scheduled Reminders ([CRM-13244](https://issues.civicrm.org/jira/browse/CRM-13244)). However, you can also use `TokenProcessor` for CiviMail by installing [FlexMailer](https://docs.civicrm.org/flexmailer/en/latest/), and you can use it for developing new logic.

The basic process in the new subsystem is

- Whenever an application's controller (e.g. for CiviMail or PDFs or scheduled reminders) needs to compose a message, it instantiates `Civi\Token\TokenProcessor`.
- The `controller` passes some information to `TokenProcessor` – namely, the `$context` and the list of `$rows`.
- The `TokenProcessor` fires an event (`TOKEN_EVALUATE`). Other modules respond with the actual token content.
- For each of the rows, the controller requests a rendered blob of text.

```php
$p = new TokenProcessor(\Civi::dispatcher(), array(
  'controller' => __CLASS__,
  'smarty' => FALSE,
));

// Fill the processor with a batch of data.
$p->addMessage('body_text', 'Hello {contact.display_name}!', 'text/plain');
$p->addRow()->context('contactId', 123);
$p->addRow()->context('contactId', 456);

// Lookup/compose any tokens which are referenced in the message.
// e.g. SELECT id, display_name FROM civicrm_contact WHERE id IN (...contextual contact ids...);
$p->evaluate();

// Display mail-merge data.
foreach ($p->getRows() as $row) {
  echo $row->render('body_text');
}
```

## Defining tokens

### hook_civicrm_tokens

The oldest and most broadly supported way to define a new token is to use [hook_civicrm_tokens](../hooks/hook_civicrm_tokens.md) and [hook_civicrm_tokenValues](../hooks/hook_civicrm_tokenValues.md). These hooks have been included with CiviCRM for a number of years, and they work with a range of mailing use-cases.

However, these hooks have some limitations:

- Encoding (HTML-vs-text) is ambiguous.
- Contextual data (adding information about the particular use-case/context) is not supported.
- All tokens have to be fully rendered for all recipients. One cannot skip unused tokens.

### Token Events (v4.7+)

If a use-case builds on the newer `TokenProcessor` (above), then an additional API is available for defining tokens. This API resolves the limitations above.

`TokenProcessor` emits two events which allow you to define new tokens. Consider this example which defines `{profile.viewUrl}` and `{profile.viewLink}`:

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
    $row->tokens('profile', 'viewUrl', 'http://example.com/profile/' . $row->context['contactId']);
    $row->tokens('profile', 'viewLink', ts("<a href='%1'>Open Profile</a>", array(
      1 => $row->tokens['profile']['viewUrl'],
    )));
  }
}
```

Some notes on the the above:

- `$row->context['...']` returns contextual data, regardless of whether you declared it at the row level or the processor level.
- To update a row's data, use the `context()` and `tokens()` methods. To read a row's data, use the $context and $tokens properties. These interfaces support several notations, which are described in the `TokenRow` class.
- You have control over the loop. You can do individual data-lookups in the loop (for simplicity) – or you can also do prefetches and batched lookups (for performance).
- To avoid unnecessary computation, you can get a list of tokens which are actually required by this mailing. Call `$e->getTokenProcessor()->getMessageTokens()`.
- In this example, we defined tokens in HTML format, and we rely on a default behavior that auto-converts between HTML and text (as needed). However, we could explicitly define HTML and plain-text variants by calling `$row->format()` and `$row->tokens()` again.
- The class `\Civi\Token\AbstractTokenSubscriber` provides a more structured/opinionated way to handle these events.
- For background on the `event dispatcher` (e.g. `listeners` vs subscribers), see [Symfony Documentation](http://symfony.com/doc/current/components/event_dispatcher/introduction.html)

The main limitation of this technique is that it only works with `TokenProcessor`. At time of writing, this is used for Scheduled Reminders and FlexMailer.
