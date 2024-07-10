<?php defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );

#[\AllowDynamicProperties]
class Convert_Owner
{
    private $_ci;
    private $_multi = TRUE;
    private $owner = [];
    private $_hasil = '';
    private $_param = [];

    function __construct()
    {
        $this->_ci =& get_instance();

        $rows        = $this->_ci->db->where( 'active', 1 )->get( 'il_owner' )->result_array();
        $this->owner = [];
        foreach( $rows as $row )
        {
            $this->owner[$row['id']] = $row['owner_name'];
        }
        $this->_clear();

    }

    function initialize( $config = array() ) {}

    function _clear()
    {
        $this->_data  = [];
        $this->_param = [];
        $this->_multi = FALSE;
    }

    function set_data( $data = [], $multi = TRUE )
    {

        $this->_data  = $data;
        $this->_multi = $multi;
        return $this;
    }

    function set_param( $params = [] )
    {
        if( is_array( $params ) )
        {
            foreach( $params as $key => $row )
            {
                $this->_param[$key] = $row;
            }
        }
        else
        {
            $this->_param[] = $params;
        }
        return $this;
    }

    function draw()
    {

        if( $this->_multi )
        {
            foreach( $this->_data as &$row )
            {
                foreach( $this->_param as $key => $prm )
                {
                    $ids   = explode( ',', $row[$prm] );
                    $owner = [];
                    foreach( $ids as $id )
                    {
                        if( array_key_exists( $id, $this->owner ) )
                        {
                            $owner[] = $this->owner[$id];
                        }
                    }
                    $hasil     = implode( '#', $owner );
                    $hasil     = format_list( $hasil, "#" );
                    $row[$key] = $hasil;
                }
            }
            unset( $row );
        }
        else
        {
            foreach( $this->_param as $key => $prm )
            {
                $ids   = explode( ',', $this->_data[$prm] );
                $owner = [];
                foreach( $ids as $id )
                {
                    if( array_key_exists( $id, $this->owner ) )
                    {
                        $owner[] = $this->owner[$id];
                    }
                }
                $hasil             = implode( '#', $owner );
                $hasil             = format_list( $hasil, "#" );
                $this->_data[$key] = $hasil;
            }
        }
        $hasil = $this->_data;
        $this->_clear();
        return $hasil;
    }
}

// END Template class

