# WPL Common
## Common components for [WPlook Studio](https://wplook.com) themes

### Directory structure
Individual components are stored in their own folders in the root directory of this repo.

### Adding components to themes
To add a common component to a theme, first install this repo using Bower, like so:
`bower install git://github.com/victoras/wpl-common.git#master`
The master branch is used to ensure all components are always up to date. As there are multiple components stored in this repo, no versioning system exists.

JavaScript components need to be included in the `Gruntfile.js` file for copying into the theme's `vendor/` directory.

SASS components need to be included in SASS files using `@import`.

PHP components need to be copied using Grunt and then included in the theme using `functions.php` and an appropriate hook.
