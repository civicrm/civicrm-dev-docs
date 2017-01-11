## When should I edit core CiviCRM?

Remember that most of the time, editing the core codebase directly
is not the recommended way for developers to customise and extend CiviCRM.

CiviCRM has two version releases per year so direct edits to the core codebase
will create upgrade issues for you. blah

There are other recommended ways for the majority of scenarios - for example
extensions, the APIs and hooks. Be sure that any edits that you make to the
core codebase are really necessary.

To help you decide, here are a couple of principles:

- Bug fixes should always be applied to core. Information on how to submit your
  bug fix will be added soon. <!--fixme!! -->
- Some (but not all) enhancements to existing features may be best applied to
  core, especially if they would be useful to others.
- New features should generally be packed as Extensions.
- If you aren't familiar with CiviCRM, by far the best way to get a sense if
  you should be editing core is by talking with the CiviCRM developer community.
