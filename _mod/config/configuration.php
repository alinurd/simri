<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
/*
|--------------------------------------------------------------------------
|  configuration
|--------------------------------------------------------------------------
| This file will contain the settings for the template library.
|
|
*/

/*
    configurai tambahan manual
*/
$config['status_cli'] = FALSE;

$config['default_config'] = [
    'show_second_sidebar'       => FALSE,
    'show_right_sidebar'        => FALSE,
    'show_title_header'         => FALSE,
    'show_header_content'       => TRUE,
    'show_action_button'        => TRUE,
    'show_column_action'        => TRUE,
    'type_action_button'        => 'drop',
    'show_list_header'          => FALSE,
    'box_list_header'           => TRUE,
    'modal_box_search'          => FALSE,
    'show_edit_header'          => FALSE,
    'show_new_header'           => FALSE,
    'show_list_footer'          => FALSE,
    'box_list_footer'           => TRUE,
    'show_edit_footer'          => FALSE,
    'show_new_footer'           => FALSE,
    'show_list_photo'           => FALSE,
    'round_button'              => TRUE,
    'tab_list'                  => FALSE,
    'box_content'               => TRUE,
    'help_tool'                 => TRUE,
    'placeholder_tool'          => TRUE,
    'left_sidebar_mini'         => FALSE,
    'themes_mode'               => 'default',
    'front_themes_mode'         => 'default',
    'max_record_lst'            => 100,
    'import_max_rows'           => 10000,
    'export_memory_limit'       => 0,
    'import_memory_limit'       => 0,
    'export_max_execution_time' => 60,
    'import_max_execution_time' => 60,
    'content_title'             => 'Title',
    'tab_title'                 => 'Title',
    'title'                     => 'Title',
    'align_label'               => 'right',
    'url_youtube'               => 'https://www.youtube.com/embed/',
];

$config['level_navigasi'] = [ 1 => 'A. Lembar Pengesahan', 2 => 'B. Lembar Distribusi', 3 => 'C. Lembar Quiz', 4 => 'D. Sejarah Perubahan', 5 => 'E. Konten' ];
$config['free_module']    = [ 'profile', 'faq', 'tiketing', "operator", "ajax", "change_password" ];
$config['upload_type']    = [ 'gif' => 'gif', 'jpg' => 'jpg', 'jpeg' => 'jpeg', 'png' => 'png', 'pdf' => 'pdf', 'pdfx' => 'pdfx', 'dox' => 'doc', 'docx' => 'docx', 'xls' => 'xls', 'xlsx' => 'xlsx', 'ppt' => 'ppt', 'pptx' => 'pptx' ];

$config['pos_menu'] = array( 'header' => 'Header', 'footer' => 'footer', 'atas-kiri' => 'atas-kiri', 'atas-kanan' => 'atas-kanan', 'bawah-kiri' => 'bawah-kiri', 'bawah-kanan' => 'bawah-kanan', 'kiri' => 'kiri', 'kanan' => 'kanan', 'atas' => 'atas', 'bawah' => 'bawah' );

$config['default_list'] = [
            'nmtbl'              => '',
            'field'              => '',
            'title'              => '',
            'type'               => 'string',
            'input'              => 'text',
            'align'              => 'left',
            'default'            => '',
            'value'              => '',
            'mode'               => 'o',
            'decimal'            => 0,
            'show'               => TRUE,
            'save'               => TRUE,
            'multiselect'        => FALSE,
            'vertical'           => FALSE,
            'inline'             => FALSE,
            'placeholder'        => '',
            'hide'               => FALSE,
            'required'           => FALSE,
            'path'               => 'upload',
            'file_type'          => 'gif|jpg|jpeg|png|pdf|xlsx|docx|ppt',
            'file_thumb'         => TRUE,
            'file_size'          => '10000',
            'file_random'        => FALSE,
            'search'             => FALSE,
            'help'               => TRUE,
            'size'               => '100',
            'size_pic'           => '132',
            'max'                => '100',
            'bidFeedBack'        => FALSE,
            'inputBox'           => '',
            'prepend'            => '',
            'append'             => '',
            'bidFeedBackAlign'   => 'left',
            'bidFeedBackContent' => '',
            'minrange'           => 0,
            'maxrange'           => 100,
            'steprange'          => 1,
            'readonly'           => FALSE,
            'disabled'           => FALSE,
            'json'               => FALSE,
            'textup'             => FALSE,
            'textlo'             => FALSE,
            'list'               => 'text',
            'line'               => FALSE,
            'line-text'          => '',
            'line-icon'          => '',
        ];

$config["image_sizes"]["tiny"]   = array( 60, 60 );
$config["image_sizes"]["small"]  = array( 280, 0 );
$config["image_sizes"]["medium"] = array( 340, 0 );
$config["image_sizes"]["large"]  = array( 800, 0 );


// paramtere data statistik dari tabel combo
$config['combo_user_type'] = [ 1 => 'All Data', 2 => 'Select Data' ];
// $config['combo_db']['user_type']['text']='Sekretariat';
// $config['combo_db']['sts_reg_event']['id']=63;
// $config['combo_db']['sts_reg_event']['text']='Peserta';
// $config['combo_db']['sts_reg_event']['text']='Peserta';
// $config['combo_db']['sts-event']['close']=66;

$config['meta'] = [
    [ 'name' => 'keywords', 'content' => 'your, tags', 'type' => 'meta', 'show' => TRUE ],
    [ 'name' => 'description', 'content' => '150 words', 'type' => 'meta', 'show' => TRUE ],
    [ 'name' => 'author', 'content' => 'Tri Untoro, tri.untoro@gmail.com', 'type' => 'meta', 'show' => TRUE ],
    [ 'name' => 'copyright', 'content' => 'Adorama Studio', 'type' => 'meta', 'show' => TRUE ],
    [ 'name' => 'language', 'content' => 'id', 'type' => 'meta', 'show' => TRUE ],
    [ 'name' => 'robots', 'content' => 'index,follow', 'type' => 'meta', 'show' => TRUE ],
    [ 'name' => 'owner', 'content' => 'ibu ari', 'type' => 'meta', 'show' => TRUE ],
    [ 'name' => 'subject', 'content' => 'your website subject', 'type' => 'meta', 'show' => FALSE ],
    [ 'name' => 'revised', 'content' => 'Sunday, July 18th, 2010, 5:15 pm', 'type' => 'meta', 'show' => FALSE ],
    [ 'name' => 'abstract', 'content' => '', 'type' => 'meta', 'show' => FALSE ],
    [ 'name' => 'topic', 'content' => '', 'type' => 'meta', 'show' => FALSE ],
    [ 'name' => 'summary', 'content' => '', 'type' => 'meta', 'show' => FALSE ],
    [ 'name' => 'Classification', 'content' => 'Business', 'type' => 'meta', 'show' => FALSE ],
    [ 'name' => 'designer', 'content' => '', 'type' => 'meta', 'show' => FALSE ],
    [ 'name' => 'reply-to', 'content' => 'email@hotmail.com', 'type' => 'meta', 'show' => FALSE ],
    [ 'name' => 'url', 'content' => 'http://www.websiteaddrress.com', 'type' => 'meta', 'show' => FALSE ],
    [ 'name' => 'identifier-URL', 'content' => 'http://www.websiteaddress.com', 'type' => 'meta', 'show' => FALSE ],
    [ 'name' => 'directory', 'content' => 'submission', 'type' => 'meta', 'show' => FALSE ],
    [ 'name' => 'category', 'content' => '', 'type' => 'meta', 'show' => FALSE ],
    [ 'name' => 'coverage', 'content' => 'Worldwide', 'type' => 'meta', 'show' => FALSE ],
    [ 'name' => 'distribution', 'content' => 'Global', 'type' => 'meta', 'show' => FALSE ],
    [ 'name' => 'rating', 'content' => 'General', 'type' => 'meta', 'show' => FALSE ],
    [ 'name' => 'revisit-after', 'content' => '7 days', 'type' => 'meta', 'show' => FALSE ],
    ];
