# FcFieldsToTitle
Provide an automatic title feature for Joomla Flexicontent items

#  Feature
This plugin allow for automatically replacing the title and alias of flexicontent items by a title generated from choosen flexicontent fields. 
Replacement take place during saving items (onContentAfterSave) so that only new and resaved items titles are changed (excepted when RefreshAllTitle option is set to Yes).
No intensive test have been done so that I recommand testing on your development site before <b>using at your own risk</b>. 

As an example, using default parameters set during install, 
All items of type <i>persons</i> will have a title set to <i>firstname lastname</i> (something like <i>John Doe</i>)
All items of type <i>clothes</i> will have a title set to <i>clothestype clothescolor clothessize</i> (something like <i>TShirt Red XL</i>)
corresponding alias will be the sanitized version of title


#  How to 

This small guide explains how to setup atomatic title for existing items of type <i>persons</i> using fields  <i>firstname</i> and <i>lastname</i> (these are fields name not alias) 
Edit the Flexicontent type <i>persons</i>
set item title to "set to ID"
Hide title from FE (and BE) forms be settings "use Title" to "Hide if Automatic"
Hide alias from FE (and BE) forms be settings "use Alias" to "No"
Edit and set FcFieldsToTitle plugin's parameters
Test plugin on some items of type <i>persons</i> (by editing and saving items
If everything works fine, activate RefreshAllTitle feature, edit and save one items to refresh all titles at once.

