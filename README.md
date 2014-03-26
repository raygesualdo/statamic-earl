#E.A.R.L. 
> The __E__xternal __A__sset and __R__esource __L__inker Plugin for Statamic

<img src="./earl.jpg" alt="Earl" height="300px" style="float:left;">Howdy y'all. Muh name's Earl. I do one thang an' one thang only: I help yuh link to external whirlygigs and thingamabobs from yer Statamic site. Have files on a CDN or some such nonsense that you need to link to? That's why yuh got me. I'll help yuh drop in simple tags that point to where you need. Sounds downright handy don't it? Well, that's cause it is.

But since I'm such a nice guy, I'll go the extra mile for yuh. I'll even let you define specific paths and file extensions you can use on demand. No more havin' to remember that really long URL some other knucklehead stores assets in everytime you want to link to a file. 

I know yer interested. Keep on readin' down to learn how to use it.

##Installation
1. [Download](https://github.com/raygesualdo/statamic-earl/releases/v1.0) or clone the plugin archive.
2. Copy `_add-ons/earl` to `_add-ons/earl`
3. Copy ` _config/add-ons/earl` to `_config/add-ons/earl`

##Use
Drop the `{{ earl }}` tag anywhere you want.

###Parameters
 - __src__: Path to the file
 - __group__: Config group to use
 - __tag__: (`js`, `css`, `img` helpers only) Set to true to generate the link inside an applicable HTML tag
 - __alt__: (`img` helper with `tag` parameter only) Alt text to be included in the `<img>` tag

###Helpers
There are three helpers for E.A.R.L.: `js`, `css`, `img`. These are very similary to the `js`, `css` and `img` helpers of the theme tag. They provide additional functionality beyond just creating links, namely being able to build the links into each asset type's HTML tag (`<script>` for `js`, etc.). The `img` helper also allows the alt text to be passed when the `tag` parameter is invoked.

##Config
Here is where the flexibilty of E.A.R.L. really comes into play. In the config file are groups of options. Let's take a look at the default settings for the __JS__ group:
```
_earl_path_js: 'path/to/js/files'
_earl_ext_js: '.js'
```
This group allows you to set two different variables, one relating to the path and one relating to the extension. Notice how both variables end with `_js`. This determines the _config group_ to which this variable belongs. You can leverage this to create config groups of your own and use them by passing the `group` parameter.

I'll show you how this works. Assume you wanted to create a config group for audio files. First, we would add the following variables to the config file:
```
_earl_path_audio: 'really/long/path/to/buried/audio/files'
_earl_ext_audio: '.mp3'

```
Second, I could use these settings by dropping in this {{ earl src="track01" group="audio"}} tag. The resulting URL would be `EARLBASEPATH/really/long/path/to/buried/audio/files/track01.mp3`.

__NOTE__: Unless the extension of the files within a group will always stay the same (this may be the case eg. javascript files), do not add an entry for the groups `ext` variable. Instead, include the extension in the `src` path.

##API
Coming soon!

<hr>

Many thanks to [Adam Koford](http://www.adamkoford.com/) for graciously allowing me to use his art for the Earl character.
