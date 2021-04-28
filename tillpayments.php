<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

/**
 * Class TillPayments
 *
 * @extends PaymentModule
 */
class TillPayments extends PaymentModule
{
    const TILL_PAYMENTS_OS_STARTING = 'TILL_PAYMENTS_OS_STARTING';
    const TILL_PAYMENTS_OS_AWAITING = 'TILL_PAYMENTS_OS_AWAITING';

    protected $config_form = false;

    public function __construct()
    {
        require_once(_PS_MODULE_DIR_ . 'tillpayments' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

        $this->name = 'tillpayments';
        $this->tab = 'payments_gateways';
        $this->version = '1.3.0';
        $this->author = 'Till Payments';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        $this->bootstrap = true;
        $this->controllers = [
            'payment',
            'callback',
            'return',
        ];

        parent::__construct();

        $this->displayName = $this->l('Till Payments');
        $this->description = $this->l('Till Payments Payment');
        $this->confirmUninstall = $this->l('confirm_uninstall');
    }

    public function install()
    {
        if (extension_loaded('curl') == false) {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');
            return false;
        }

        if (!parent::install()
            || !$this->registerHook('paymentOptions')
            || !$this->registerHook('displayPaymentReturn')

            || !$this->registerHook('payment')
            || !$this->registerHook('displayAfterBodyOpeningTag')
            || !$this->registerHook('header')
        ) {
            return false;
        }

        $this->createOrderState(static::TILL_PAYMENTS_OS_STARTING);
        $this->createOrderState(static::TILL_PAYMENTS_OS_AWAITING);

        // set default configuration
        Configuration::updateValue('TILL_PAYMENTS_HOST', 'https://gateway.tillpayments.com/');

        return true;
    }

    public function uninstall()
    {
        Configuration::deleteByName('TILL_PAYMENTS_ENABLED');
        Configuration::deleteByName('TILL_PAYMENTS_HOST');

        foreach ($this->getCreditCards() as $creditCard) {
            $prefix = strtoupper($creditCard);
            Configuration::deleteByName('TILL_PAYMENTS_' . $prefix . '_TITLE');
            Configuration::deleteByName('TILL_PAYMENTS_' . $prefix . '_ACCOUNT_USER');
            Configuration::deleteByName('TILL_PAYMENTS_' . $prefix . '_ACCOUNT_PASSWORD');
            Configuration::deleteByName('TILL_PAYMENTS_' . $prefix . '_API_KEY');
            Configuration::deleteByName('TILL_PAYMENTS_' . $prefix . '_SHARED_SECRET');
            Configuration::deleteByName('TILL_PAYMENTS_' . $prefix . '_INTEGRATION_KEY');
            Configuration::deleteByName('TILL_PAYMENTS_' . $prefix . '_SEAMLESS');
        }

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        if (((bool)Tools::isSubmit('submitTillPaymentsModule')) == true) {
            $form_values = $this->getConfigFormValues();
            foreach (array_keys($form_values) as $key) {
                $key = str_replace(['[', ']'], '', $key);
                $val = Tools::getValue($key);
                if (is_array($val)) {
                    $val = \json_encode($val);
                }
                if ($key == 'TILL_PAYMENTS_HOST') {
                    $val = rtrim($val, '/') . '/';
                }
                Configuration::updateValue($key, $val);
            }
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        return $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTillPaymentsModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$this->getConfigForm()]);
    }

    private function getCreditCards()
    {
        /**
         * Comment/disable adapters that are not applicable
         */
        return [
            'cc' => 'CreditCard',
            'visa' => 'Visa',
            'mastercard' => 'MasterCard',
            'amex' => 'Amex',
            // 'diners' => 'Diners',
            // 'jcb' => 'JCB',
            // 'discover' => 'Discover',
            // 'unionpay' => 'UnionPay',
            // 'maestro' => 'Maestro',
            // 'uatp' => 'UATP',
        ];
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        $form = [
            'form' => [
                'tabs' => [
                    'General' => 'General',
                    'CreditCard' => 'Credit Card',
                ],
                'legend' => [
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'name' => 'TILL_PAYMENTS_ENABLED',
                        'label' => $this->l('Enable'),
                        'tab' => 'General',
                        'type' => 'switch',
                        'is_bool' => 1,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => 'Enabled',
                            ],
                            [
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => 'Disabled',
                            ],
                        ],
                    ],
                    [
                        'name' => 'TILL_PAYMENTS_HOST',
                        'label' => $this->l('Host'),
                        'tab' => 'General',
                        'type' => 'text',
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];

        foreach ($this->getCreditCards() as $creditCard) {

            $prefix = strtoupper($creditCard);

            $form['form']['input'][] = [
                'name' => 'line',
                'type' => 'html',
                'tab' => 'CreditCard',
                'html_content' => '<h3 style="margin-top: 10px;">' . $creditCard . '</h3>',
            ];

            $form['form']['input'][] = [
                'name' => 'TILL_PAYMENTS_' . $prefix . '_ENABLED',
                'label' => $this->l('Enable'),
                'tab' => 'CreditCard',
                'type' => 'switch',
                'is_bool' => 1,
                'values' => [
                    [
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => 'Enabled',
                    ],
                    [
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => 'Disabled',
                    ],
                ],
            ];
            $form['form']['input'][] = [
                'name' => 'TILL_PAYMENTS_' . $prefix . '_TITLE',
                'label' => $this->l('Title'),
                'tab' => 'CreditCard',
                'type' => 'text',
            ];
            $form['form']['input'][] = [
                'name' => 'TILL_PAYMENTS_' . $prefix . '_ACCOUNT_USER',
                'label' => $this->l('User'),
                'tab' => 'CreditCard',
                'type' => 'text',
            ];
            $form['form']['input'][] = [
                'name' => 'TILL_PAYMENTS_' . $prefix . '_ACCOUNT_PASSWORD',
                'label' => $this->l('Password'),
                'tab' => 'CreditCard',
                'type' => 'text',
            ];
            $form['form']['input'][] = [
                'name' => 'TILL_PAYMENTS_' . $prefix . '_API_KEY',
                'label' => $this->l('API Key'),
                'tab' => 'CreditCard',
                'type' => 'text',
            ];
            $form['form']['input'][] = [
                'name' => 'TILL_PAYMENTS_' . $prefix . '_SHARED_SECRET',
                'label' => $this->l('Shared Secret'),
                'tab' => 'CreditCard',
                'type' => 'text',
            ];
            $form['form']['input'][] = [
                'name' => 'TILL_PAYMENTS_' . $prefix . '_INTEGRATION_KEY',
                'label' => $this->l('Integration Key'),
                'tab' => 'CreditCard',
                'type' => 'text',
            ];
            $form['form']['input'][] = [
                'name' => 'TILL_PAYMENTS_' . $prefix . '_SEAMLESS',
                'label' => $this->l('Seamless Integration'),
                'tab' => 'CreditCard',
                'type' => 'switch',
                'is_bool' => 1,
                'values' => [
                    [
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => 'Enabled',
                    ],
                    [
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => 'Disabled',
                    ],
                ],
            ];
            //            $form['form']['input'][] = [
            //                'name' => 'line',
            //                'type' => 'html',
            //                'tab' => 'CreditCard',
            //                'html_content' => '<hr>',
            //            ];
        }

        return $form;
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        $values = [
            'TILL_PAYMENTS_ENABLED' => Configuration::get('TILL_PAYMENTS_ENABLED', null),
            'TILL_PAYMENTS_HOST' => Configuration::get('TILL_PAYMENTS_HOST', null),
        ];

        foreach ($this->getCreditCards() as $creditCard) {
            $prefix = strtoupper($creditCard);
            $values['TILL_PAYMENTS_' . $prefix . '_ENABLED'] = Configuration::get('TILL_PAYMENTS_' . $prefix . '_ENABLED', null);
            $values['TILL_PAYMENTS_' . $prefix . '_TITLE'] = Configuration::get('TILL_PAYMENTS_' . $prefix . '_TITLE') ?: $creditCard;
            $values['TILL_PAYMENTS_' . $prefix . '_ACCOUNT_USER'] = Configuration::get('TILL_PAYMENTS_' . $prefix . '_ACCOUNT_USER', null);
            $values['TILL_PAYMENTS_' . $prefix . '_ACCOUNT_PASSWORD'] = Configuration::get('TILL_PAYMENTS_' . $prefix . '_ACCOUNT_PASSWORD', null);
            $values['TILL_PAYMENTS_' . $prefix . '_API_KEY'] = Configuration::get('TILL_PAYMENTS_' . $prefix . '_API_KEY', null);
            $values['TILL_PAYMENTS_' . $prefix . '_SHARED_SECRET'] = Configuration::get('TILL_PAYMENTS_' . $prefix . '_SHARED_SECRET', null);
            $values['TILL_PAYMENTS_' . $prefix . '_INTEGRATION_KEY'] = Configuration::get('TILL_PAYMENTS_' . $prefix . '_INTEGRATION_KEY', null);
            $values['TILL_PAYMENTS_' . $prefix . '_SEAMLESS'] = Configuration::get('TILL_PAYMENTS_' . $prefix . '_SEAMLESS', null);
        }

        return $values;
    }

    /**
     * Payment options hook
     *
     * @param $params
     * @throws Exception
     * @return bool|void
     */
    public function hookPaymentOptions($params)
    {
        if (!$this->active || !Configuration::get('TILL_PAYMENTS_ENABLED', null)) {
            return;
        }

        $result = [];

        $years = [];
        $years[] = date('Y');
        for ($i = 1; $i <= 10; $i++) {
            $years[] = $years[0] + $i;
        }
        $this->context->smarty->assign([
            'months' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'years' => $years,
        ]);

        foreach ($this->getCreditCards() as $key => $creditCard) {

            $prefix = strtoupper($creditCard);

            if (!Configuration::get('TILL_PAYMENTS_' . $prefix . '_ENABLED', null)) {
                continue;
            }

            $payment = new PaymentOption();
            $payment
                ->setModuleName($this->name)
                ->setCallToActionText($this->l(Configuration::get('TILL_PAYMENTS_' . $prefix . '_TITLE', null)))
                ->setAction($this->context->link->getModuleLink($this->name, 'payment', [
                        'type' => $creditCard,
                    ], true));

            if (Configuration::get('TILL_PAYMENTS_' . $prefix . '_SEAMLESS', null)) {

                $this->context->smarty->assign([
                    'paymentType' => $creditCard,
                    'id' => 'p' . bin2hex(random_bytes(10)),
                    'action' => $payment->getAction(),
                    'integrationKey' => Configuration::get('TILL_PAYMENTS_' . $prefix . '_INTEGRATION_KEY', null),
                ]);

                $payment->setInputs([['type' => 'input', 'name' => 'test', 'value' => 'value']]);

                $payment->setForm($this->fetch('module:tillpayments' . DIRECTORY_SEPARATOR . 'views' .
                    DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'front' . DIRECTORY_SEPARATOR . 'seamless.tpl'));
            }

            $payment->setLogo(
                Media::getMediaPath(_PS_MODULE_DIR_ . $this->name . '/views/img/creditcard/' . $key . '.png')
            );

            $result[] = $payment;
        }

        return count($result) ? $result : false;
    }

    public function hookDisplayPaymentReturn($params)
    {
        if (!$this->active || !Configuration::get('TILL_PAYMENTS_ENABLED', null)) {
            return;
        }

        return $this->display(__FILE__, 'views/templates/hook/payment_return.tpl');
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        if ($this->context->controller instanceof OrderControllerCore && $this->context->controller->page_name == 'checkout') {
            $uri = '/modules/tillpayments/views/js/front.js';
            $this->context->controller->registerJavascript(sha1($uri), $uri, ['position' => 'bottom']);
        }
    }

    public function hookDisplayAfterBodyOpeningTag()
    {
        if ($this->context->controller instanceof OrderControllerCore && $this->context->controller->page_name == 'checkout') {
            $host = Configuration::get('TILL_PAYMENTS_HOST', null);
            return '<script data-main="payment-js" src="' . $host . 'js/integrated/payment.min.js"></script><script>window.tillPaymentsPayment = {};</script>';
        }

        return null;
    }

    /**
     * This method is used to render the payment button,
     * Take care if the button should be displayed or not.
     */
    public function hookPayment($params)
    {
        $currency_id = $params['cart']->id_currency;
        $currency = new Currency((int)$currency_id);

        if (in_array($currency->iso_code, $this->limited_currencies) == false) {
            return false;
        }

        $this->smarty->assign('module_dir', $this->_path);

        return $this->display(__FILE__, 'views/templates/hook/payment.tpl');
    }

    private function createOrderState($stateName)
    {
        if (!\Configuration::get($stateName)) {
            $orderState = new \OrderState();
            $orderState->name = [];

            switch ($stateName) {
                case self::TILL_PAYMENTS_OS_STARTING:
                    $names = [
                        'de' => 'Till Payments Bezahlung gestartet',
                        'en' => 'Till Payments payment started',
                    ];
                    break;
                case self::TILL_PAYMENTS_OS_AWAITING:
                default:
                    $names = [
                        'de' => 'Till Payments Bezahlung ausständig',
                        'en' => 'Till Payments payment awaiting',
                    ];
                    break;
            }

            foreach (\Language::getLanguages() as $language) {
                if (\Tools::strtolower($language['iso_code']) == 'de') {
                    $orderState->name[$language['id_lang']] = $names['de'];
                } else {
                    $orderState->name[$language['id_lang']] = $names['en'];
                }
            }
            $orderState->invoice = false;
            $orderState->send_email = false;
            $orderState->module_name = $this->name;
            $orderState->color = '#076dc4';
            $orderState->hidden = false;
            $orderState->logable = false;
            $orderState->delivery = false;
            $orderState->add();

            \Configuration::updateValue(
                $stateName,
                (int)($orderState->id)
            );
        }
    }
}
