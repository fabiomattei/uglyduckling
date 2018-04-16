<?php

namespace templates\blocks\wrappers;

use core\blocks\BaseBlock;

class BaseList extends BaseBlock {

	function __construct( $item_list, $title = 'List' ) {
		$this->item_list = $item_list;
		$this->title     = $title;
	}
	
    function show() {
		$out = '<div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">'.$this->title.'</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">';
        $out .= $this->write_links();
        $out .= '</div>
<!-- /.col-lg-12 -->
        </div>
        <div class="row">
        <div class="col-sm-12">
          <div class="white-box">
            <div class="table-responsive">
            <table id="myTable" class="table table-striped">
            <thead>
				<tr>';
        $out .= $this->write_titles();
        $out .= '<th>&nbsp;</th>
		</tr>
		</thead>
        <tbody>';
		foreach ($this->item_list as $item) {
			$out .= '<tr>';
            $out .= $this->write_item( $item );
			$out .= $this->write_buttons( $item );
			$out .= '</tr>';
		}
        $out .= '</tbody>
            </table>
            </div> <!-- table-responsive  -->
          </div> <!-- white-box  -->
        </div> <!-- white-box  -->
        </div> <!-- row  -->';
		return $out;
    }
	
	function write_titles() {
		return '<th style="width: 50px;">#</th>
				<th>Name</th>';             
	}
	
	function write_item( $item ) {
		return '<td>'. $item->title .'</td>';             
	}
	
	function write_buttons( $item ) {
		return '<td></td>';
	}
	
	function write_links() {
		return '';
	}

	function addToHead_old() {
        return '<link href="'.BASEPATH.'assets/plugins/bower_components/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
        <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />';
    }

    function addToFoot_old() {
        return '<!-- Custom Theme JavaScript -->
        <script src="'.BASEPATH.'assets/js/custom.min.js"></script>
        <script src="'.BASEPATH.'assets/plugins/bower_components/datatables/jquery.dataTables.min.js"></script>
        <script>
        $(document).ready(function(){
            $("#myTable").DataTable();
        });
        </script>';
    }

}
