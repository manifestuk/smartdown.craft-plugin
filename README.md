# Smartdown for Craft #
Smartdown for Craft is a Twig Filter which brings the unbridled joy of
[Markdown Extra][md_extra] and [Smartypants][smartypants] to [Craft][craft].

[craft]:http://buildwithcraft.com
[md_extra]:http://michelf.ca/projects/php-markdown/
[smartypants]:http://michelf.ca/projects/php-smartypants/

## Usage ##
Full installation and usage instructions are available in [the `/src/README.md` file][usage].

[usage]: src/README.md "Installation and usage instructions."

## Build process ##
Smartdown uses Gulp to package everything for release. The current process needs
improvement, but it's a decent start.

The example assumes we're creating a patch release, version `1.2.1`.

`````
$> gulp bump-patch && gulp build
$> git commit -am "Release 1.2.1"
$> git tag -a 1.2.1 -m "Release 1.2.1"
$> git push && git push --tags
`````

In the above example, Gulp automatically bumps the patch version in the
`src/smartdown/config.json` file, and generates a new "release" zip file (in
this case `releases/smartdown-1.2.1.zip`).

Committing the changes and tagging the release are still a manual operation at
this point in time.

To create a minor or major release, replace `gulp bump-patch` with
`gulp bump-minor` or `gulp bump-major`, respectively.
