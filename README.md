## FcFieldsToTitle
Provide an automatic title feature for Joomla Flexicontent items

##  Feature
This plugin allow for automatically replacing the title and alias of flexicontent items by a title generated from choosen flexicontent fields. 
Replacement take place during saving items (onContentAfterSave) so that only new and resaved items titles are changed (excepted when RefreshAllTitle option is set to Yes).
No intensive test have been done so that I recommand testing on your development site before **using at your own risk**. 

As an example, using default parameters set during install, 
* All items of type *persons* will have a title set to *firstname lastname* (something like *John Doe*)
* All items of type *clothes* will have a title set to *clothestype clothescolor clothessize* (something like *TShirt Red XL*)
* corresponding alias will be the sanitized version of title

##  How to 
Using the plugin is describe in the beackend or FcFieldsToTitle
This small guide explains how to setup atomatic title for existing items of type *persons* using fields  *firstname* and *lastname* (these are fields name not alias) 
* Edit the Flexicontent type *persons*
* set item title to "set to ID"
* Hide title from FE (and BE) forms be settings "use Title" to "Hide if Automatic"
* Hide alias from FE (and BE) forms be settings "use Alias" to "No"
* Edit and set FcFieldsToTitle plugin's parameters
* Test plugin on some items of type *persons* (by editing and saving items
* If everything works fine, activate RefreshAllTitle feature, edit and save one items to refresh all titles at once.

## TODO
By Default, FcFieldsToTitle modify item title OnContentAfterSave event. This require to edit and save an item to get automatic title for this item. 

FcFieldsToTitle has a built-in RefreshAllTitle feature to apply the automatic title to all items at once. This feature while important should be coded in Ajax to get a more efficent plugin. (Sory I do not have Ajax skill to do that!)

