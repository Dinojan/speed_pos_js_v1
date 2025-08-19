<?php 
class _system{
    public function _cofig(){
        $config = [
            // "AppInfo"=> [
            "AppName"=>"Speed POS",
            "Version"=> "1.0.0",
            "Copyrights"=> "Northern IT Hub",
            "CopyrightsUrl"=> "https://www.northernithub.com/",
            "logo"=> "1_logo.png",
            // ]
        ];
        return $config;
    }
    public function _themStyle(){
        $detail = [
            'body_class' => [
                'dark_mode' => '',
                'hold_transition' => 'hold-transition',
                'sidebar_mini' => 'sidebar-mini',
                'layout_fixed' => 'layout-fixed',
                'layout_navbar_fixed' => 'layout-navbar-fixed',
                'layout_footer_fixed' => 'layout-footer-fixed',
                'sidebar_collapse' => '',
                // 'sidebar_mini_md' => 'sidebar-mini-md',
                // 'sidebar_mini_xs' => 'sidebar-mini-xs',
                'text_sm' => 'text-sm',
                'accent_color' => 'accent-primary',
        
            ],
            'brandLogo' => [
                'brand_logo_text' => 'text-sm',
                'logo_variants' => ''
            ],
            'footerClass' => [
                'footer_text' => 'text-sm',
            ],
            'nav_class' => [
                'navbar_expand' => 'navbar-expand',
                'border_bottom_0' => '',
                'dropdown_legacy' => 'dropdown-legacy',
                'text_sm' => 'text-sm',
                'nav_bg' => 'navbar-dark',
            ],
            'side_nav_class' => [
                'nav_flat' => 'nav-flat',
                'nav_legacy' => 'nav-legacy',
                'nav_compact' => 'nav-compact',
                'nav_child_indent' => 'nav-child-indent',
                'nav_collapse_hide_child' => '',
                'sidebar_no_expand' => '',
                'sidebar_txt_sm' => 'text-sm',
        
            ],
            'asideClass' => [
                'dark_sidebar_variants' => 'sidebar-dark-primary',
                'light_sidebar_variants' => ''
            ]
        
        ];
        return serialize($detail);
    }
}