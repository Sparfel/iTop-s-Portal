<?php
class Portal_Controller_Action_Helper_DataTable extends Zend_Controller_Action_Helper_Abstract 
{
    /**
     * addMessage() - Add a message to flash message
     *
     * @param  string $message
     * @return Zend_Controller_Action_Helper_FlashMessenger Provides a fluent interface
     */
	
	protected $_baseurl;
	protected $_request;
	protected $_json_path;
	protected $_nb_display;
	protected $_fields;
	protected $_module;
	protected $_controller;
	protected $_language;
	
	protected $_AsearchCriteria; // Array with the search criterias from the search form
	protected $Alabel; // An array wityh all translated labels 
	
	/**
	 * Pour fonctionner, il faut passer en paramètre à datatableHeader un tableau contenant les champs que l'on souhaite 
	 * voir apparaitre dans la grille. Ces champs doivent exister dans l'objet DBtable d'ou proviennent les données
	 * array ( 
    							array
    								('field' => 'ref',
    								'label' => 'Ref',
    							 	'width' => '10%',
    							 	'link'  =>  true,
    							    'target' => array('controller'=> 'ctrl', 'action' => 'edit'),
    							    'link_param =>' array('service_id' => 'service_id'),
    							 	'sort'	=> 'desc'), // ou desc
    							 array
    							 	('field' => 'title',
    							 	'label' => 'Title',
    							 	'width' => '80%',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> 'asc'
    							 	),
    							 array
    							 	('field' => 'start_date',
    							 	'label' => 'Start date', 
    							 	'width' => '10%',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> ''
    							 	)
    							);
	 
	 *La notion de target pose problème car lors que la langue est activé,
	 *cela ajoute à l'url index/language/en/, décalant les paramètres.
	 *L'ID cible n'est alors plus récupéré et la target est inopérante.
	 *Bidouille pour ne plus l'utiliser ...
	 *A améliorer !
	 */
	
	public function __construct() {
		
		$this->_baseurl =  $this->getRequest()->getBaseUrl();
    	$this->_request =  $this->getRequest()->getRequestUri();
    	$this->_module = $this->getRequest()->getParam('module');
    	$this->_controller = $this->getRequest()->getParam('controller');
    	
    	//$this->_json_path =  $this->_baseurl.$this->_request.'/getdata/format/json';
    	//le _json_path est fait différemment car des que la langue apparait dans l'url, ça plante le path du json
    	//Zend_Debug::dump($this->_request);
    	$this->_json_path = $this->_baseurl.'/'.$this->_module.'/'.$this->_controller.'/getdata/format/json';
    	 
    	//Zend_Debug::dump('GetDataAction !');
    	$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
		$this->_nb_display = $config->pagination->par;
		
		$session = new Zend_Session_Namespace('Zend_Auth');
		// usefull for the links => do not change the language if we follow the link, the language is in the url
		$this->_language = $session->pref->_language;
		
		//We initialize the Array of Label's translation
		$this->LabelTranslate();
		
	}
	
	// We change the path because we add some filters in the selections criterias
	public function IsSearchMode(){
		$this->_json_path = $this->_baseurl.'/'.$this->_module.'/'.$this->_controller.'/getdataSearchMode/format/json';
		
	}
	
    public function datatableHeader($fields)
    {
		$this->_fields = $fields;
    	$Columns = '';
		// The sort is not perfect because we sort in the order of the colomun
		$sort = '';
		$no_col = 0;
     	foreach ($this->_fields as $field) {
     		
     		//We delete the target ! but what are the consequences ?
     		if ($field['link']) {
     			//We check here the target of a hypothetic link
     			if (is_Array($field['target'])) {
     				if (is_null($field['target']['action']))
     					{	// If no action, we delete '/'
     						// If controller is not defined too, we put nothing here
     						$ctrl_act = $field['target']['controller'];}
     				 
     				else 
     					{$ctrl_act = $field['target']['controller']."/".$field['target']['action'];}
     				
     				/*$href_field = 'id';
     				if (array_key_exists('field', $field['target'])) {
	     				if (is_null($field['target']['field']) AND strlen($field['target']['field'])> 0)
	     					{
	     						$href_field = 'id';
	     					}
	     				else {
	     					$href_field = $field['target']['field'];
	     					}
     				}
     				$Columns .= "{ 'mData': '".$field['field']."', sDefaultContent: '','mRender': function ( data, type, full ) {
        							return '<a href=\"".$ctrl_act."/id/'+row.".$href_field."+'\" style=\"width:".$field['width'].";\">'+data+'</a>';
     							}
								},";*/
     					
     				$Columns .= "{ 'mData': '".$field['field']."', sDefaultContent: '','mRender': function ( data, type, full ) {
        							return '<a href=\"".$ctrl_act."/id/'+row.id+'\" style=\"width:".$field['width'].";\">'+data+'</a>';
     							}
								},";
    	 			
     			}
     			else if (is_Array($field['link_param']))
     			{
     				$param = '';
     				foreach ($field['link_param'] as $key => $value){
     					$param .= '/'.$value.'/\'+row.'.$value.'+';
     				} 
     				
     				if (strlen($field['target'])>0){ $target = "/".$field['target'];}
     				else {$target = null;}
     				 
     				$Columns .= "{ 'mData': '".$field['field']."',
		     						sDefaultContent: '',
		     						'mRender': function ( data, type, row )
		     								{
		        								return '<a href=\"".$this->_request.$target."/id/'+row.id+'".$param."'\">'+data+'</a>';
		      								}
							},";
     			}
     			else
     			{
	     			if (strlen($field['target'])>0){ $target = "/".$field['target'];} 
	     				else {$target = null;}    			

	     			$Columns .= "{ 'mData': '".$field['field']."',
		     						sDefaultContent: '',
		     						'mRender': function ( data, type, row )
		     								{
		        								return '<a href=\"".$this->_request.$target."/id/'+row.id+'\">'+data+'</a>';
		      								}
							},";
     			
     			}
     	 	}
     	 	else
     	 	{     		
       			$Columns .= "{ 'mData': '".$field['field']."', sDefaultContent: '',
						},";}
       		
       		if (($field['sort']=='asc') or ($field['sort']=='desc')) {$sort .= "[".$no_col.",'".$field['sort']."'],";};
       		$no_col++;
       	}
    	
        $script = "jQuery(document).ready(function() {
				
        		 jQuery('#grille').dataTable( {
					'iDisplayLength': ".$this->_nb_display.",
					'aLengthMenu': [[100, 200, 400, -1], [100, 200, 400, 'All']],
					'aaSorting': [".$sort."], 
					'bProcessing': true,
					'bDestroy': true,
					'bAutoWidth' : false,
					//'bServerSide': true,
					'sAjaxSource':'".$this->_json_path."',
					
					'aoColumns': [".$Columns."],
					'sPaginationType': 'full_numbers',
					'oLanguage': 
						{ 'sProcessing':     '".$this->Alabel['sProcessing']."',
							'sSearch':         '".$this->Alabel['sSearch']."',
							'sLengthMenu':     '".$this->Alabel['sLengthMenu']."',
							'sInfo':           '".$this->Alabel['sInfo']."',
							'sInfoEmpty':      '".$this->Alabel['sInfoEmpty']."',
							'sInfoFiltered':   '".$this->Alabel['sInfoFiltered']."',
							'sInfoPostFix':    '',
							'sLoadingRecords': '".$this->Alabel['sLoadingRecords']."',
							'sZeroRecords':    '".$this->Alabel['sZeroRecords']."',
							'sEmptyTable':     '".$this->Alabel['sEmptyTable']."',
							'oPaginate': {
								'sFirst':      '".$this->Alabel['sFirst']."',
								'sPrevious':   '".$this->Alabel['sPrevious']."',
								'sNext':       '".$this->Alabel['sNext']."',
								'sLast':       '".$this->Alabel['sLast']."'
								},
						'oAria': 
							{
							'sSortAscending':  '".$this->Alabel['sSortAscending']."',
							'sSortDescending': '".$this->Alabel['sSortDescending']."'
							}
						},
					 'sDom': '<\'top\'T><\"top\"ip<\"clear\">lf>rt>',
								
					 'tableTools': {'sSwfPath': '/layouts/frontoffice/swf/copy_csv_xls_pdf.swf',
    				 				'aButtons': [
						                 {
						                    'sExtends': 'collection',
						                    'sButtonText': '".$this->Alabel['sButtonText1']."',
											'aButtons': [
													{
									                    'sExtends': 'copy',
									                    'sButtonText': '".$this->Alabel['sButtonText2']."'
									                },
									                'csv',
									                'xls',
									                {
									                    'sExtends': 'pdf',
									                    'sPdfOrientation': 'landscape',
									                    'sPdfMessage': '".$this->Alabel['sPdfMessage']."'
									                },
									                 {
									                    'sExtends': 'print',
									                    'sButtonText': '".$this->Alabel['sButtonText3']."',
														'sInfo': '".$this->Alabel['sInfo5']."'
									                }
													]
						                }
						            ]
    					},					
								 
    			});
				
							
				
				});";
   
		return $script;
    }

    // on génère le tableau <\"top\"ip<\"clear\">lf>rt>
   	public function datatableTable() {
   		$Columns = '';
   		$table = '<table cellpadding="" cellspacing="2" border="0" class="display" id="grille" width="100%">
					<thead>
						<tr>';
   		foreach ($this->_fields as $field) {
   				$Columns .= '<th style="width:'.$field['width'].';">'.$field['label'].'</th>';	
   		 }
		$table .= $Columns."</tr>
					</thead>
					<tbody>
					<tr>
						<td colspan='".count($this->_fields)."' class='dataTables_empty'>Récupération des données du serveur</td>
					</tr>
					</tbody>
					<tfoot>
					</tfoot>
				</table>";
   		return $table;
   }
   
   private function LabelTranslate(){
	   	$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
	   	if (null === $viewRenderer->view) {
	   		$viewRenderer->initView();
	   	}
	   	$view = $viewRenderer->view;
	   	//We use " and not ' in the translation string to be able to use \' in the string.
	   	$this->Alabel['sProcessing'] =  $view->translate("Traitement en cours...");
	   	$this->Alabel['sSearch'] =      $view->translate("Rechercher :");
	   	$this->Alabel['sLengthMenu'] =  $view->translate("_MENU_ éléments");
	   	$this->Alabel['sInfo'] = 		$view->translate("Affichage de l\\'élément _START_ &agrave; _END_ sur _TOTAL_ éléments");
	   	$this->Alabel['sInfo1'] =       $view->translate("Affichage de l\\'élément");
	   	$this->Alabel['sInfo2'] = 		$view->translate("à");
	   	$this->Alabel['sInfo3'] =		$view->translate("sur");
	   	$this->Alabel['sInfo4'] =		$view->translate("éléments");
	   	$this->Alabel['sInfoEmpty'] =   $view->translate("Affichage de l\\'élément 0 à 0 sur 0 éléments");
	   	$this->Alabel['sInfoFiltered'] =$view->translate("(filtré de _MAX_ éléments au total)");
	   	$this->Alabel['sInfoPostFix'] = $view->translate("");
	   	$this->Alabel['sLoadingRecords']=$view->translate("Chargement en cours...");
	   	$this->Alabel['sZeroRecords'] = $view->translate("Aucun élément à afficher");
	   	$this->Alabel['sEmptyTable'] =  $view->translate("Aucune donnée disponible dans le tableau");
	   	$this->Alabel['sFirst'] =      	$view->translate("Premier");
   		$this->Alabel['sPrevious'] =   	$view->translate("Précédent");
   		$this->Alabel['sNext'] =       	$view->translate("Suivant");
   		$this->Alabel['sLast'] =       	$view->translate("Dernier");
   		$this->Alabel['sSortAscending']=$view->translate("activer pour trier la colonne par ordre croissant");
   		$this->Alabel['sSortDescending']=$view->translate("activer pour trier la colonne par ordre décroissant");
   		$this->Alabel['sExtends'] = 	$view->translate("collection");
   		$this->Alabel['sButtonText1'] = 	$view->translate("Export");
   		$this->Alabel['sButtonText2'] = 	$view->translate("Copier");
   		$this->Alabel['sPdfMessage'] =	$view->translate("Listes des tickets.");
   		$this->Alabel['sButtonText3'] = 	$view->translate("Imprimer");
   		$this->Alabel['sInfo5'] = 		$view->translate("<h1>Visualisation en mode édition</h1><p>Utiliser les fonction d\\'édition du navigateur. Presser \\'Echappe\\' pour revenir à la page</p>");
	  	

   }
   
   
   
  }
