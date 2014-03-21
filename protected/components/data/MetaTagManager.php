<?php

/**
 *  HTML meta tag manager
 *
 *  @see http://code.lancepollard.com/complete-list-of-html-meta-tags/
 */
class MetaTagManager
{

    /**
     *  all tags
     */
    protected static $_tags = array();

    /**
     *  set meta tag
     *  @param string - $tag
     *  @param any - $value
     */
    protected static function _setTag( $group, $tag, $value, $isFilter=true )
    {
        if ( $isFilter ) {
            $tag   = htmlspecialchars($tag);
            $value = htmlspecialchars($value);
        }
        self::$_tags[$group][$tag] = $value;
    }

    /**
     *  get meta tag value
     *  @param string - $tag
     */
    protected static function _getTag( $group, $tag )
    {
        if( isset(self::$_tags[$group]) && 
            isset(self::$_tags[$group][$tag])
        ) {
            return self::$_tags[$group][$tag];
        }
        return null;
    }

    /* --------------------------------------------------------------------------------
        get/set meta tag attrib
    -------------------------------------------------------------------------------- */
    public static function setName( $tag, $value, $isFilter=true )
    {
        self::_setTag('name',$tag,$value,$isFilter);
    }
    public static function getName( $tag )
    {
        return self::_getTag('name',$tag);
    }

    public static function setHttpEquiv( $tag, $value, $isFilter=true )
    {
        self::_setTag('httpEquiv',$tag,$value,$isFilter);
    }
    public static function getHttpEquiv( $tag )
    {
        return self::_getTag('httpEquiv',$tag);
    }

    public static function setProperty( $tag, $value, $isFilter=true )
    {
        self::_setTag('property',$tag,$value,$isFilter);
    }
    public static function getProperty( $tag )
    {
        return self::_getTag('property',$tag);
    }

    /* --------------------------------------------------------------------------------
        
    -------------------------------------------------------------------------------- */
    /**
     *  get meta tag value
     *  @param string - $tag
     */
    public static function init()
    {
        self::$_tags = array(
            'httpEquiv' => array(),
            'name'      => array(),
            'property'  => array()
        );
        self::setHttpEquiv('Content-Type', 'text/html; charset=utf-8');
        self::setName('robots', 'follow, index');

        //
        if ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) {
            self::setName('robots', 'nofollow, noindex');
        }
    }

    /**
     *  render html
     *  @return html string
     */
    public static function render()
    {
        $html = '';
        $tags = self::$_tags;
        foreach ( $tags as $group => $item ) {
            foreach ( $item as $tag => $value ) {
                switch ( $group ) {
                    case 'httpEquiv':
                        $html .= '<meta http-equiv="'. $tag .'" content="'. $value .'" />';
                        break;
                    case 'name':
                        $html .= '<meta name="'. $tag .'" content="'. $value .'" />';
                        break;
                    case 'property':
                        $html .= '<meta property="'. $tag .'" content="'. $value .'" />';
                        break;
                    default:
                        $html .= '<meta/>';
                }
                $html .= "\n";
            }
        }
        return $html;
    }

}