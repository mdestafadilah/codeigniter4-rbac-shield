<?php

// TODO: Dinamis Menu
// Source: https://chatgpt.com/s/t_6920e46cca608191b7ab2b7c0398f31e

use CodeIgniter\HTTP\URI;

function active_menu(string $url): string
{
    $current = uri_string();       // contoh: 'users/edit/1'
    $target  = trim($url, '/');    // contoh: '/users' → 'users'

    // cocokkan prefix untuk highlight
    return (strpos($current, $target) === 0) ? 'active' : '';
}

// ==================== Dynamic Menu Rendering ====================

if (!function_exists('render_dynamic_menu')) {
    /**
     * Render dynamic menu from database
     * 
     * @param array $menus Array of menu items from database
     * @param string $currentUrl Current URL to check active state
     * @return string HTML output
     */
    function render_dynamic_menu($menus, $currentUrl = '')
    {
        if (empty($currentUrl)) {
            $currentUrl = current_url();
        }

        $html = '';

        foreach ($menus as $menu) {
            // Skip inactive menus
            if (!$menu['is_active']) {
                continue;
            }

            // Check permission
            if (!empty($menu['permission']) && !has_permission($menu['permission'])) {
                continue;
            }

            // Render based on type
            switch ($menu['type']) {
                case 'section':
                    $html .= render_section($menu);
                    break;
                case 'group':
                    $html .= render_group($menu, $currentUrl);
                    break;
                case 'item':
                    $html .= render_item($menu, $currentUrl);
                    break;
            }
        }

        return $html;
    }
}

if (!function_exists('render_section')) {
    /**
     * Render section title
     */
    function render_section($menu)
    {
        return '<div class="menu-title">' . esc($menu['label']) . '</div>';
    }
}

if (!function_exists('render_group')) {
    /**
     * Render menu group with collapse
     */
    function render_group($menu, $currentUrl)
    {
        $children = $menu['children'] ?? [];
        
        // Filter children based on permission
        $visibleChildren = array_filter($children, function($child) {
            if (!$child['is_active']) {
                return false;
            }
            if (!empty($child['permission']) && !has_permission($child['permission'])) {
                return false;
            }
            return true;
        });

        // Don't show group if no visible children
        if (empty($visibleChildren)) {
            return '';
        }

        // Check if any child is active
        $isActive = false;
        foreach ($visibleChildren as $child) {
            if (!empty($child['url']) && strpos($currentUrl, trim($child['url'], '/')) !== false) {
                $isActive = true;
                break;
            }
        }

        $collapseId = 'collapse' . $menu['id'];
        $icon = $menu['icon'] ?? 'fas fa-database';

        $html = '<div class="menu-item">';
        $html .= '<a class="menu-link" data-bs-toggle="collapse" href="#' . $collapseId . '"';
        $html .= ' aria-expanded="' . ($isActive ? 'true' : 'false') . '">';
        $html .= '<i class="' . esc($icon) . '"></i>';
        $html .= '<span>' . esc($menu['label']) . '</span>';
        $html .= '<i class="fas fa-chevron-down ms-auto"></i>';
        $html .= '</a>';
        $html .= '</div>';

        $html .= '<div class="collapse ' . ($isActive ? 'show' : '') . '" id="' . $collapseId . '">';
        foreach ($visibleChildren as $child) {
            $html .= render_item($child, $currentUrl, true);
        }
        $html .= '</div>';

        return $html;
    }
}

if (!function_exists('render_item')) {
    /**
     * Render menu item
     */
    function render_item($menu, $currentUrl, $isChild = false)
    {
        $url = $menu['url'] ?? '#';
        $icon = $menu['icon'] ?? '';
        $isActiveItem = !empty($url) && strpos($currentUrl, trim($url, '/')) !== false;
        
        // Special handling for logout
        $onclick = '';
        if ($menu['label'] === 'Logout' || strpos($url, 'javascript:void(0)') !== false) {
            $onclick = ' onclick="confirmLogout()"';
            $url = 'javascript:void(0)';
        } else {
            $url = base_url($url);
        }

        $html = '<div class="menu-item' . ($isChild ? ' ms-3' : '') . '">';
        $html .= '<a href="' . esc($url) . '"' . $onclick;
        $html .= ' class="menu-link ' . ($isActiveItem ? 'active' : '') . '">';
        if ($icon) {
            $html .= '<i class="' . esc($icon) . '"></i>';
        }
        $html .= '<span>' . esc($menu['label']) . '</span>';
        $html .= '</a>';
        $html .= '</div>';

        return $html;
    }
}

if (!function_exists('has_child_with_permission')) {
    /**
     * Check if menu has at least one child with permission
     */
    function has_child_with_permission($menu)
    {
        if (empty($menu['children'])) {
            return false;
        }

        foreach ($menu['children'] as $child) {
            if (!$child['is_active']) {
                continue;
            }
            if (empty($child['permission']) || has_permission($child['permission'])) {
                return true;
            }
        }

        return false;
    }
}
