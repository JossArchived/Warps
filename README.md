# Warps

A simple warp system, you can add any warp location and teleport to it easily from a menu

If you encounter any bugs, have suggestions or questions, [create an issue](https://github.com/Josscoder/Warps/issues/new).

## Setup

1) Download the latest version [here](https://poggit.pmmp.io/ci/Josscoder/Warps#)
2) Put the .phar in your plugins folder
3) And finally, start your server!

## Commands

- /addwarp | This command will open a form, in which you must put a name or identifier, which does not already exist between the warps and which is not empty
- /removewarp | This command will open a form if you have warps created, where you can select the warp that you want to eliminate, once eliminated, it has no turn back!
- /reloadwarps | This will reload all the warps, this works in case you edit the warps in the configuration and you want the changes to be loaded

These commands require the player to be an operator!

- /warps | This command is used to see the list of available warps!


## Configuration

```yml
item:
  allowed_give_item_to_join: false
  custom_name: "&a&lWarps"

warps:
  - test;252.2534:65:246.767:171.47085571289:15.317016601562:world 
    # the identifier or name is the one before the symbol ";" in this case it is "test",
    # after the symbol already mentioned, you will see the symbol ":" that separates the location where the warp is,
    # example "x: y: z: worldName"
```
