Javascript tests ensure that CiviCRM's JS logic is working as expected – 
for example, ensuring that a custom JS widget adapts correctly to different inputs.

Buildkit includes the tools required for running the tests. Alternatively, 
download Karma and Jasmine by running "npm install" in the civicrm directory.

These test were introduced in Civi v4.6 and are written in the AngularJS 
conventions using [karma] and [jasmine].

## Running Javascript Tests

```bash
cd /path/to/civicrm
npm test
```

[karma]: https://karma-runner.github.io/1.0/index.html
[jasmine]: https://jasmine.github.io/2.1/introduction.html
