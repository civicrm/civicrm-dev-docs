# Differences Between API v3 and v4

APIv4 is broadly similar to APIv3. Both are designed for reading and writing data.
Both use *entities*, *actions*, and *parameters*. However, APIv4 is specifically a
breaking-change which aims to reduce *ambiguity* and improve *flexibility* and *consistency*.

This document walks through a list of specific differences.  As you consider
them, it may help to have a concrete example expressed in both APIv3 and APIv4:

<!-- Would be nice if Markdown made it easier to do side-by-side comparison... -->
<table>
  <thead>
    <tr>
      <th>APIv3</th>
      <th>APIv4</th>
    </tr>
  </thead>
  <tbody>
    <tr>
<td>
  <em>Procedural-array style:</em><br/>
  <div class="codehilite"><pre>
 1: $res = civicrm_api3('Contact', 'get', [
 2:   'sequential' => 1,
 3:   'check_permissions' => 0,
 4:   'first_name' => 'Bob',
 5:   'return' => 'id,display_name',
 6:   'options' => [
 7:     'limit' => 2,
 8:     'offset' => 2,
 9:   ],
10: ]);
11:
12: foreach ($res['values'] as $row) {
13:   echo $row['display_name'];
14: }
</pre></div>
</td>
<td>
  <em>Procedural-array style:</em><br/>
  <div class="codehilite"><pre>
 1: $res = civicrm_api4('Contact', 'get', [
 2:   'checkPermissions' => FALSE,
 3:   'where' => [['first_name', '=', 'Bob']],
 4:   'select' => ['id', 'display_name'],
&nbsp;
 6:   'limit' => 2,
 7:   'offset' => 2,
&nbsp;
 9: ]);
10:
11: foreach ($res as $row) {
12:   echo $row['display_name'];
13: }
</pre></div>

  <em>Object-oriented style:</em><br/>
  <div class="codehilite"><pre>
 1: $res = \Civi\Api4\Contact::get()
 2:  ->setCheckPermissions(FALSE)
 3:  ->addWhere(['first_name', '=', 'Bob'])
 4:  ->addSelect(['id', 'display_name'])
&nbsp;
 6:  ->setLimit(2)
 7:  ->setOffset(2)
&nbsp;
 9:  ->execute();
10:
11: foreach ($res as $row) {
12:   echo $row['display_name'];
13: }
</pre></div>
</td>
    </tr>
  </tbody>
</table>


## API Wrapper

* APIv4 supports two notations in PHP:
    * Procedural/array style: `civicrm_api4('Entity', 'action', $params)`
    * Object-oriented style: `\Civi\Api4\Entity::action()->...->execute()`
* When using OOP style in an IDE, most actions and parameters can benefit from auto-completion and type-checking.
* `$checkPermissions` always defaults to `TRUE`. In APIv3, the default depended on the environment (`TRUE` in REST/Javascript; `FALSE` in PHP).
* Instead of APIv3's `sequential` param, a more flexible `index` controls how results are returned. In traditional style is is the 4th parameter to the api function:
    * Passing a string will index all results by that key e.g. `civicrm_api4('Contact', 'get', $params, 'id')` will index by id.
    * Passing a number will return the result at that index e.g. `civicrm_api4('Contact', 'get', $params, 0)` will return the first result and is the same as `\Civi\Api4\Contact::get()->execute()->first()`. `-1` is the equivalent of `$result->last()`.
* When [chaining](../v4/chaining.md) API calls together, back-references to values from the main API call must be explicitly given (discoverable in the API Explorer).

## Actions 
* For `Get`, the default `limit` has changed. If you send an API call without an explicit limit, then it will return *all* records. (In v3, it would silently apply a default of 25.) However, if you use the API Explorer, it will *recommend* a default limit of 25.
* The `Create` action is now only used for creating *new* items (no more implicit update by passing an id to v3 `create`).
* The `Save` action in v4 is most similar to v3's `create` - it accepts one or more records to create or update, infering the action based on the presence of `id` in each record.
* `Update` and `Delete` can be performed on multiple items at once by specifying a `where` clause, vs a single item by id in v3.
  Unlike v3, they will not complain if no matching items are found to update/delete and will return an empty result instead of an error.
* `getsingle` is gone, use `$result->first()` or `index` `0`.
* `getoptions` is no longer a standalone action, but part of `getFields`.

## Output  
* Output is an object (`Result` aka [`ArrayObject`](https://www.php.net/manual/en/class.arrayobject.php)) rather than a plain `array`.
* In PHP, you can iterate over the `ArrayObject` (`foreach ($myResult as $record)`), or you can call methods like `$result->first()` or `$result->indexBy('foo')`.
* By default, results are indexed sequentially (`0,1,2,3,...` like APIv3's `sequential => 1`). You may optionally index by `id`, `name`, or any other field, as in:
    * (Procedural-style; use `$index` parameter): `civicrm_api4('Contact', 'get', [], 'id')`
    * (OOP-style; use `indexBy()` method): `\Civi\Api4\Contact::get()->execute()->indexBy('id')`
* Custom fields are refered to by name rather than id. E.g. use `constituent_information.Most_Important_Issue` instead of `custom_4`.

## Input
APIv4 reflects the ongoing efforts present through the lifecycle of APIv3 toward uniform and discreet input parameters.

For a little history... If you used early versions of APIv3, you might have written some code like this:

```php
civicrm_api3('Contact', 'get', array(
  'check_permissions' => 0,
  'first_name' => 'Elizabeth',
  'return' => 'id,display_name',
  'rowCount' => 1000,
  'offset' => 2,
));
```

You may notice that there are no subordinate arrays -- everything goes into one flat list of parameters.
As the system grew, this became a bit awkward:

* What happens if you want to filter on a field named `return` or `action` or `rowCount`?
* How do you ensure that the same option gets the same name across all entities (`rowCount` vs `limit`)?
* Why does `first_name` use snake_case while `rowCount` uses lowerCamelCase?
* Why is `Contact.get` the only API to support `rowCount`?

Over time, APIv3 evolved so that this example would be more typical:

```php
civicrm_api3('Contact', 'get', [
  'check_permissions' => FALSE,
  'first_name' => 'Elizabeth',
  'return' => ['id','display_name'],
  'options' => ['limit' => 1000, 'offset' => 2],
]);
```

Observe:

* The `options` adds a place where you can define parameters without concern for conflicts.
* The new generation of `options` are more standardized - they often have generic implementations that work with multiple entities/actions.
* The top-level still contains a mix of *option* fields (like `return`) and *data* or *query* fields (like `first_name`).
* The old options at the top-level are deprecated but still around.

APIv4 presented an opportunity to *break backward compatibility* and thereby *become more consistent*. In APIv4, a typical call would look like:

```php
civicrm_api4('Contact', 'get', [
  'checkPermissions' => FALSE,
  'where' => [['first_name', '=', 'Elizabeth']],
  'select' => ['id', 'display_name'],
  'limit' => 1000,
  'offset' => 2,
]);
```

Key things to note:

* The `options` array is completely gone. The params array *is* the list of options.
* Most of the options in the params array have shared/generic implementations - ensuring consistent naming and behavior for every api entity.
* The *data* fields (e.g. `id`, `display_name`, and `first_name`) no longer appear at the top. They always appear beneath some other option, such as `where` or `select`.
