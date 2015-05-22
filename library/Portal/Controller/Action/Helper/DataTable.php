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
	
	protected $_AsearchCriteria; // tableau contenant les critères de recherche issu du formulaire de recherche
	
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
		//utile pour les lien => ne pas changer de langue si on suit le lien, code langue étant dans l'url 
		$this->_language = $session->pref->_language;
		
	}
	
	//On change le path car on va ajouter aux filtres des crotères de sélections
	public function IsSearchMode(){
		$this->_json_path = $this->_baseurl.'/'.$this->_module.'/'.$this->_controller.'/getdataSearchMode/format/json';
		
	}
	
    public function datatableHeader($fields)
    {
		$this->_fields = $fields;
    	
		//echo '<a href="'.$this->url(array('module'=>'atelys','controller'=>'atelys','action'=>'edit','id'=>12)).'">';
		$Columns = '';
		// le tri reste moyen car il trie dans l'ordre des colonnes ... un peu moyen ...
		$sort = '';
		$no_col = 0;
     	foreach ($this->_fields as $field) {
     		
     		//on vire la target ! mais quelles sont les conséquences ?
     		if ($field['link']) {
     			//Ici on vérifie la cible de l'éventuel lien
     			if (is_Array($field['target'])) {
     				if (is_null($field['target']['action']))
     					{	// si l'acion n'est pas renseignée, on enlève le '/'
     						// si le controller n'est pas non plus renseigné, alors on ne met rien.
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
						{ 'sProcessing':     'Traitement en cours...',
							'sSearch':         'Rechercher :',
							'sLengthMenu':     '_MENU_ éléments',
							'sInfo':           'Affichage de l\'élément _START_ &agrave; _END_ sur _TOTAL_ éléments',
							'sInfoEmpty':      'Affichage de l\'élément 0 à 0 sur 0 éléments',
							'sInfoFiltered':   '(filtré de _MAX_ éléments au total)',
							'sInfoPostFix':    '',
							'sLoadingRecords': 'Chargement en cours...',
							'sZeroRecords':    'Aucun élément à afficher',
							'sEmptyTable':     'Aucune donnée disponible dans le tableau',
							'oPaginate': {
								'sFirst':      'Premier',
								'sPrevious':   'Précédent',
								'sNext':       'Suivant',
								'sLast':       'Dernier'
								},
						'oAria': 
							{
							'sSortAscending':  ': activer pour trier la colonne par ordre croissant',
							'sSortDescending': ': activer pour trier la colonne par ordre décroissant'
							}
						},
					 'sDom': '<\'top\'T><\"top\"ip<\"clear\">lf>rt>',
								
					 'tableTools': {'sSwfPath': '/layouts/frontoffice/swf/copy_csv_xls_pdf.swf',
    				 				'aButtons': [
						                 {
						                    'sExtends': 'collection',
						                    'sButtonText': 'Export',
											'aButtons': [
													{
									                    'sExtends': 'copy',
									                    'sButtonText': 'Copier'
									                },
									                'csv',
									                'xls',
									                {
									                    'sExtends': 'pdf',
									                    'sPdfOrientation': 'landscape',
									                    'sPdfMessage': 'Listes des tickets.'
									                },
									                 {
									                    'sExtends': 'print',
									                    'sButtonText': 'Imprimer',
														'sInfo': '<h1>Visualisation en mode édition</h1><p>Utiliser les fonction d\'édition du navigateur. Presser \'Echappe\' pour revenir à la page</p>'
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
   
  }
