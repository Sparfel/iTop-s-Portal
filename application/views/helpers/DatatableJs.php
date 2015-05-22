<?php 
class Zend_View_Helper_DatatableJs extends Zend_View_Helper_Abstract
{

	public function datatableJs($msg)
	{
		echo $msg;
	}
	
	public function datatableHeader(){
		
		$script = "$(document).ready(function() {
				 $('#example').dataTable( {
					'iDisplayLength': 100,
					'aLengthMenu': [[100, 200, 400, -1], [100, 200, 400, 'All']],
					'bProcessing': true,
					//'bServerSide': true,
					'sAjaxSource':'".$this->view->url(array('action'=>'getdata', 'format'=>'json'))."',
					'aoColumns': [
							      { 'mData': 'name', sDefaultContent: 'n/a' },
							      { 'mData': 'description', sDefaultContent: '' }
							    ],
					'sPaginationType': 'full_numbers',
					'oLanguage': 
						{ 'sProcessing':     'Traitement en cours...',
							'sSearch':         'Rechercher&nbsp;:',
							'sLengthMenu':     '_MENU_ &eacute;l&eacute;ments',
							'sInfo':           'Affichage de l\'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments',
							'sInfoEmpty':      'Affichage de l\'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments',
							'sInfoFiltered':   '(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)',
							'sInfoPostFix':    '',
							'sLoadingRecords': 'Chargement en cours...',
							'sZeroRecords':    'Aucun &eacute;l&eacute;ment &agrave; afficher',
							'sEmptyTable':     'Aucune donnée disponible dans le tableau',
							'oPaginate': {
								'sFirst':      'Premier',
								'sPrevious':   'Pr&eacute;c&eacute;dent',
								'sNext':       'Suivant',
								'sLast':       'Dernier'
								},
						'oAria': 
							{
							'sSortAscending':  ': activer pour trier la colonne par ordre croissant',
							'sSortDescending': ': activer pour trier la colonne par ordre décroissant'
							}
						},
					'sDom': 'ip<\"clear\">l<\"top\">rt<\"bottom\"><\"clear\">'							
				});
				
			} );";
		return $script;
		
	}
	
	
}