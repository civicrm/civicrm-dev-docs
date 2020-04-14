# Database localized fields and upgrades

## PHP code related to database upgrades

We try to reduce the number of strings that will be practically never seen by administrators. Very unlikely error messages should be left untranslated.

For example, in `CRM_Upgrade_Incremental_php_FourSeven`, `addTask()` task names such as "Upgrade DB to ..." should be translated:

```php
$this->addTask(
  ts('Upgrade DB to %1: SQL', array(1 => '4.3.5')),
  'task_4_3_x_runSql',
  $rev
);
```

Very specific one-time tasks should not be translated (wrapped in `ts`). Administrators are very unlikely to see such strings. If they do, they will probably need the original English string in order to get support on the forums. They are also strings that are very hard to translate because of lack of context.

For example, do not translate:

```php
$this->addTask(
  'Update financial_account_id in financial_trxn table',
  'updateFinancialTrxnData',
  $rev
);
```

## Localized fields

Any value stored in the database that may depend on the language must be localizable. For example:

* A contribution page title or description,
* A group title or description,
* A configurable string to display on forms (ex: a custom "submit" button label).

However, since localizable fields add a certain technical complexity, the following type of fields are not localized:

* Contact information, such as the first name, nickname, etc.
* Address fields.

While there are many cities where street names can officially be in multiple languages (or have official transliterations), users usually enter their address only in one language. It is rarely required to store the address translation (one exception: event locations, which is currently a known limitation).

Similarly, the first and last name of an individual may be written in different alphabets (ex: Latin and Cyrillic), but this is not a frequent use-case worth the complexity. Administrators can workaround this by creating custom fields.

In order to define a field as localizable, the [XML schema definition](../framework/database/schema-definition.md) for that field must have the following tag:

```
<localizable>true</localizable>
```

If a new field was not initially tagged as localizable, the upgrade must explicitly convert the field. See the section below on localised fields schema changes.

## SQL upgrades

SQL upgrades must account for two use-cases:

* localized CiviCRM: the values in the database are in one language only, so no new database fields are created, however
* multi-lingual CiviCRM: a typical `value` field will be removed, and replaced with `value_en_US`, `value_fr_FR`, and so on.

There are two variables exposed to the sql templates when upgrading: `$multilingual` makes it possible to test if the database is multi-lingual, while `$locales` lists the enabled languages. For example:

```smarty
{if $multilingual}
  {foreach from=$locales item=locale}
    UPDATE civicrm_option_group
    SET label_{$locale} = description_{$locale}
    WHERE label_{$locale} IS NULL;
  {/foreach}
{else}
  UPDATE civicrm_option_group
  SET `label` = `description`
  WHERE `label` IS NULL;
{/if}
```

However the `{localize}` helper for SQL upgrades (e.g. statements in `CRM/Upgrade/Incremental/sql/*.mysql.tpl` files)  allows you do the same thing without explicitly looping on locales. This `UPDATE` statement handles both multi-lingual and non-multi-lingual cases.

```smarty
UPDATE `civicrm_premiums`
SET
  {localize field="premiums_nothankyou_label"}
    premiums_nothankyou_label = '{ts escape="sql"}No thank-you{/ts}'
  {/localize};
```

On a multi-lingual site with English and French enabled, this would evaluate to:

```sql
UPDATE `civicrm_premiums`
SET
  premiums_nothankyou_label_en_US = 'No thank-you',
  premiums_nothankyou_label_fr_FR = 'Non merci';
```

The `{ts}` tag translates the string based on the default language that is set _when the upgrade is being run_. In the above example if the upgrade was run while the default language was French, that column would be set to "Merci non". It would be good to fix this so that the values for each enabled language are translated when a translated string is available.

For an `INSERT` example, the following query:

```smarty
INSERT INTO civicrm_option_value (
  option_group_id,
  {localize field='label'}label{/localize},
  value,
  name,
  filter,
  weight,
  is_active )
VALUES (
  @option_group_id_ere,
  {localize}'{ts escape="sql"}Participant Role{/ts}'{/localize},
  1,
  'participant_role',
  0,
  1,
  1 );
```

On a multi-lingual site with English and French enabled, the above would evaluate to:

```sql
INSERT INTO civicrm_option_value (
  option_group_id,
  label_en_US,
  label_fr_FR,
  value,
  name,
  filter,
  weight,
  is_active )
VALUES (
  @option_group_id_ere,
  'Participant Role',
  'Rôle du participant',
  1,
  'participant_role',
  0,
  1,
  1 );
```

## Localised fields schema changes

Two use-cases:

1. An existing field in CiviCRM was not tagged in the [XML schema](../framework/database/schema-definition.md) as `<localizable>` (ex: the `title` in `civicrm_survey`, before CiviCRM 4.5). After adding the `<localize>` tag in the XML file, you must also add an upgrade snippet for existing databases. Example, from `sql/4.1.0.mysql.tpl`:

    ```smarty
    {if $multilingual}
      {foreach from=$locales item=locale}
          ALTER TABLE civicrm_pcp_block ADD link_text_{$locale} varchar(255);
          UPDATE civicrm_pcp_block SET link_text_{$locale} = link_text;
      {/foreach}
      ALTER TABLE civicrm_pcp_block DROP link_text;
    {/if}
    ```

2. If a localized field was removed or added, the schema does odd things during the upgrade to figure out which fields are mutli-lingual. Rebuilding the multi-lingual schema will check in `CRM/Core/I18n/SchemaStructure.php` for the fields used by the database views. If the schema is changed, copy the `SchemaStructure.php` from the master branch to, for example, `SchemaStructure_4_5_alpha1.php`. The 4.5 alpha1 will then read this file when rebuilding the schema, see `CRM/Core/I18n/Schema.php` for more information (`getLatestSchema`). i.e. during an upgrade, we may be upgrading from 4.0 to 4.5, and when rebuilding the views at each stage, we need to load the correct schema version. Since we do not have a schema file for each minor version, CiviCRM will attempt to load the most relevant schema version to the version of the upgrade step being run.
