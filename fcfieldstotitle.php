<?php
/**
 * @version		0.1
 * @package		Fcfieldstotitle Content plugin 
 * @author    	Christophe Seguinot - 
 * @copyright
 * @license		GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 * This plugin is used to trigger all events related to content on Ircica Website
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.plugin.plugin' );
class plgContentFcfieldstotitle extends JPlugin {

	function onContentAfterSave($context, &$article, $isNew){ 
		
		if (!defined('FLEXI_VERSION')) return true; 
		
		if ($context<>"com_content.article") return true; 
		
		$this->title_from_flexi_fields($article);
		
	}

	private function title_from_flexi_fields(&$article){ 
	
        $db = JFactory::getDBO();
            
		// Frontend and Backend Load english language file for 'fcfieldtotitle' plugin then override with current language file
		JFactory::getLanguage()->load('plg_content_fcfieldstotitle', JPATH_SITE .'/plugins/content/fcfieldstotitle',	null, true);
            // Get plugin info
            $plugin = JPluginHelper::getPlugin ( 'content', 'fcfieldstotitle' );
            // Load params into and empty object
             $plgParams = new JRegistry();

            if ($plugin && isset($plugin->params)) {
                    $plgParams->loadString($plugin->params);
            }
            $refreshAllTitle = $plgParams->get('refreshAllTitle', false);
            if ($refreshAllTitle){
            // Reset refreshAllTitle parameter
                $table = new JTableExtension(JFactory::getDbo());
                $table->load(array('element' => 'fcfieldstotitle'));
                $plgParams->set('refreshAllTitle', 0);
                $table->set('params', $plgParams->toString());
                $table->store();
            }
            $itemTypes=explode(';',$plgParams->get('itemTypes', ''));

            // Search Fieldsnames values
            $titleFieldsStrings = explode(';',$plgParams->get('titleFieldsStrings', ''));
            
            if (count($itemTypes)<>count($titleFieldsStrings))
            {
                    if(JDEBUG)
                    {
                        JFactory::getApplication()->enqueueMessage(JTEXT::_('FC_FIELD2TITLE_INCONSISTENT_PARAMS'), 'Error');
                    }
                    return true;
            }
            
            for ( $i= 0 ; $i < count($itemTypes) ; $i++ )
            {
                // foreach itempType
                
                $itemType=$itemTypes[$i];
                
                // TODO we could test if type exist (not necessary) 
                
                $titleFields=explode(',',$titleFieldsStrings[$i]);
                
                // When in Debug mode, issue an error message if fields do not exists for this type
               if(JDEBUG)
	           {
	               foreach ($titleFields as $titleField)
	               {
		               $db->setQuery("SELECT count(*) FROM rria3_flexicontent_fields AS f 
		                                INNER JOIN rria3_flexicontent_fields_type_relations as ftr on f.id = ftr.field_id
		                                INNER JOIN rria3_flexicontent_types as t ON ftr.type_id=t.id
		                                WHERE t.name='$itemType' AND f.name='$titleField'");
	                	$count = $db->loadResult();
	                	if (! $count) 
	                	{
		                		JFactory::getApplication()->enqueueMessage(JText::sprintf('FC_FIELD2TITLE_FIELD_NOT_EXISTS', $titleField, $itemType), 'Error');
	                	}
	                }
                }              
                $quotedtitleFieldsString= "'". implode($titleFields,"','") ."'";

                $query ="SELECT DISTINCT item_id FROM rria3_flexicontent_fields_item_relations AS fir 
                                INNER JOIN rria3_flexicontent_fields AS f ON fir.field_id=f.id 
                                INNER JOIN rria3_flexicontent_fields_type_relations as ftr on f.id = ftr.field_id
                                INNER JOIN rria3_flexicontent_types as t ON ftr.type_id=t.id
                                WHERE t.name='$itemType' AND f.name in($quotedtitleFieldsString)";
                if ($refreshAllTitle)
                {
                	JFactory::getApplication()->enqueueMessage(JText::sprintf('FC_FIELD2TITLE_REFRESH', $itemType), 'Message');
                }
                else
                {
                    // Normal usage restrict to current item
                    $query .= " AND fir.item_id={$article->item_id}";
                }
                $db->setQuery($query);
                $item_ids = $db->loadColumn();
                // Process found item(s)
                foreach ($item_ids as $item_id)
                {
                    $title =array();
                    foreach ($titleFields as $titleField) 
                    {
                            $db->setQuery("SELECT a.value FROM #__flexicontent_fields_item_relations AS a 
                                    INNER JOIN #__flexicontent_fields AS b ON a.field_id=b.id 
                                    WHERE item_id=$item_id AND b.name='$titleField'");
                            $title[]= $db->loadResult();
                    }
                    
                    if ($title) 
                    {
						// Change title
	                    $title = implode(' ',$title);
	                    $alias = JFilterOutput::stringURLSafe($title);
	                    $title = $db->quote($title);
	                    
	                    // It is unnecessary to check for duplicate alias: FC add item id "id-" before $alias
	
	                    // Update title and alias (for all languages if Component like Falang in use)
	                    $query = "UPDATE #__content SET title=$title,alias='$alias'  WHERE id=$item_id";
	                    $db->setQuery($query);
	                    $db->execute();
                    } 
                }

            }            
            return true;
	}

} // END CLASS
