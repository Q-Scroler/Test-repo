<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class StampCustomizer extends Module
{
    public function __construct()
    {
        $this->name = 'stampcustomizer';
        $this->tab = 'front_office_features';
        $this->version = '1.2.1';
        $this->author = 'Quentin Dsanti';
        $this->need_instance = 0;
        parent::__construct();
        $this->displayName = $this->l('Custom stamp MTB');
        $this->description = $this->l('Allows customers to customize a stamp text.');
    }

    public function install()
    {
        return parent::install()
            && Configuration::updateValue('SC_BORDER', 1)
            && Configuration::updateValue('SC_WIDTH', 150)
            && Configuration::updateValue('SC_HEIGHT', 80)
            && Configuration::updateValue('SC_COLOR', '#000000')
            && Configuration::updateValue('SC_BUNDLE_PRODUCT', 0)
            && Configuration::updateValue('SC_SHAPE', 'rectangle')
            && Configuration::updateValue('SC_PRICE_PER_LINE', 1)
            && Configuration::updateValue('SC_LINES', 3)
            && $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        return parent::uninstall()
            && Configuration::deleteByName('SC_BORDER')
            && Configuration::deleteByName('SC_WIDTH')
            && Configuration::deleteByName('SC_HEIGHT')
            && Configuration::deleteByName('SC_COLOR')
            && Configuration::deleteByName('SC_BUNDLE_PRODUCT')
            && Configuration::deleteByName('SC_SHAPE')
            && Configuration::deleteByName('SC_PRICE_PER_LINE')
            && Configuration::deleteByName('SC_LINES');
    }

    public function getContent()
    {
        if (Tools::isSubmit('submit_'.$this->name)) {
            Configuration::updateValue('SC_BORDER', (int)Tools::getValue('SC_BORDER'));
            Configuration::updateValue('SC_WIDTH', (int)Tools::getValue('SC_WIDTH'));
            Configuration::updateValue('SC_HEIGHT', (int)Tools::getValue('SC_HEIGHT'));
            Configuration::updateValue('SC_COLOR', Tools::getValue('SC_COLOR'));
            Configuration::updateValue('SC_BUNDLE_PRODUCT', (int)Tools::getValue('SC_BUNDLE_PRODUCT'));
            Configuration::updateValue('SC_SHAPE', Tools::getValue('SC_SHAPE'));
            Configuration::updateValue('SC_PRICE_PER_LINE', (float)Tools::getValue('SC_PRICE_PER_LINE'));
            Configuration::updateValue('SC_LINES', (int)Tools::getValue('SC_LINES'));
            return $this->displayConfirmation($this->l('Settings updated')).$this->renderForm();
        }

        return $this->renderForm();
    }

    protected function renderForm()
    {
        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Stamp Settings'),
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => $this->l('Border size (px)'),
                        'name' => 'SC_BORDER',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Width (px)'),
                        'name' => 'SC_WIDTH',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Height (px)'),
                        'name' => 'SC_HEIGHT',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Color'),
                        'name' => 'SC_COLOR',
                    ],
                    [
                        'type' => 'select',
                        'label' => $this->l('Shape'),
                        'name' => 'SC_SHAPE',
                        'options' => [
                            'query' => [
                                ['id' => 'rectangle', 'name' => $this->l('Rectangle')],
                                ['id' => 'round', 'name' => $this->l('Round')],
                                ['id' => 'oval', 'name' => $this->l('Oval')],
                            ],
                            'id' => 'id',
                            'name' => 'name',
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Price per line'),
                        'name' => 'SC_PRICE_PER_LINE',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Maximum lines'),
                        'name' => 'SC_LINES',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Bundle product ID'),
                        'name' => 'SC_BUNDLE_PRODUCT',
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->submit_action = 'submit_'.$this->name;
        $helper->tpl_vars = [
            'fields_value' => [
                'SC_BORDER' => Configuration::get('SC_BORDER'),
                'SC_WIDTH' => Configuration::get('SC_WIDTH'),
                'SC_HEIGHT' => Configuration::get('SC_HEIGHT'),
                'SC_COLOR' => Configuration::get('SC_COLOR'),
                'SC_SHAPE' => Configuration::get('SC_SHAPE'),
                'SC_PRICE_PER_LINE' => Configuration::get('SC_PRICE_PER_LINE'),
                'SC_LINES' => Configuration::get('SC_LINES'),
                'SC_BUNDLE_PRODUCT' => Configuration::get('SC_BUNDLE_PRODUCT'),
            ],
        ];

        return $helper->generateForm([$fields_form]);
    }

    public function hookDisplayHome($params)
    {
        $this->context->controller->addCSS($this->_path.'views/css/stampcustomizer.css');
        $this->context->controller->addJS($this->_path.'views/js/stampcustomizer.js');
        $shape = Configuration::get('SC_SHAPE');
        $borderRadius = $shape === 'rectangle' ? '0' : '50%';
        $this->context->smarty->assign([
            'stamp_border' => Configuration::get('SC_BORDER'),
            'stamp_width' => Configuration::get('SC_WIDTH'),
            'stamp_height' => Configuration::get('SC_HEIGHT'),
            'stamp_color' => Configuration::get('SC_COLOR'),
            'stamp_shape' => $shape,
            'stamp_border_radius' => $borderRadius,
            'stamp_price_per_line' => Configuration::get('SC_PRICE_PER_LINE'),
            'stamp_lines' => (int)Configuration::get('SC_LINES'),
        ]);
        return $this->display(__FILE__, 'views/templates/hook/stampcustomizer.tpl');
    }
}
