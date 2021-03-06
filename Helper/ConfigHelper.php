<?php

namespace Algolia\AlgoliaSearch\Helper;

use Magento\Framework\Locale\Currency;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class ConfigHelper
{
    const ENABLE_FRONTEND                      = 'algoliasearch_credentials/credentials/enable_frontend';
    const ENABLE_BACKEND                       = 'algoliasearch_credentials/credentials/enable_backend';
    const LOGGING_ENABLED                      = 'algoliasearch_credentials/credentials/debug';
    const IS_POPUP_ENABLED                     = 'algoliasearch_credentials/credentials/is_popup_enabled';
    const APPLICATION_ID                       = 'algoliasearch_credentials/credentials/application_id';
    const API_KEY                              = 'algoliasearch_credentials/credentials/api_key';
    const SEARCH_ONLY_API_KEY                  = 'algoliasearch_credentials/credentials/search_only_api_key';
    const INDEX_PREFIX                         = 'algoliasearch_credentials/credentials/index_prefix';
    const IS_INSTANT_ENABLED                   = 'algoliasearch_credentials/credentials/is_instant_enabled';

    const REPLACE_CATEGORIES                   = 'algoliasearch_instant/instant/replace_categories';
    const INSTANT_SELECTOR                     = 'algoliasearch_instant/instant/instant_selector';
    const FACETS                               = 'algoliasearch_instant/instant/facets';
    const MAX_VALUES_PER_FACET                 = 'algoliasearch_instant/instant/max_values_per_facet';
    const SORTING_INDICES                      = 'algoliasearch_instant/instant/sorts';
    const XML_ADD_TO_CART_ENABLE               = 'algoliasearch_instant/instant/add_to_cart_enable';

    const NB_OF_PRODUCTS_SUGGESTIONS           = 'algoliasearch_autocomplete/autocomplete/nb_of_products_suggestions';
    const NB_OF_CATEGORIES_SUGGESTIONS         = 'algoliasearch_autocomplete/autocomplete/nb_of_categories_suggestions';
    const NB_OF_QUERIES_SUGGESTIONS            = 'algoliasearch_autocomplete/autocomplete/nb_of_queries_suggestions';
    const AUTOCOMPLETE_SECTIONS                = 'algoliasearch_autocomplete/autocomplete/sections';
    const EXCLUDED_PAGES                       = 'algoliasearch_autocomplete/autocomplete/excluded_pages';
    const MIN_POPULARITY                       = 'algoliasearch_autocomplete/autocomplete/min_popularity';
    const MIN_NUMBER_OF_RESULTS                = 'algoliasearch_autocomplete/autocomplete/min_number_of_results';

    const NUMBER_OF_PRODUCT_RESULTS            = 'algoliasearch_products/products/number_product_results';
    const PRODUCT_ATTRIBUTES                   = 'algoliasearch_products/products/product_additional_attributes';
    const PRODUCT_CUSTOM_RANKING               = 'algoliasearch_products/products/custom_ranking_product_attributes';
    const RESULTS_LIMIT                        = 'algoliasearch_products/products/results_limit';
    const SHOW_SUGGESTIONS_NO_RESULTS          = 'algoliasearch_products/products/show_suggestions_on_no_result_page';

    const CATEGORY_ATTRIBUTES                  = 'algoliasearch_categories/categories/category_additional_attributes';
    const INDEX_PRODUCT_COUNT                  = 'algoliasearch_categories/categories/index_product_count';
    const CATEGORY_CUSTOM_RANKING              = 'algoliasearch_categories/categories/custom_ranking_category_attributes';

    const IS_ACTIVE                            = 'algoliasearch_queue/queue/active';
    const NUMBER_OF_JOB_TO_RUN                 = 'algoliasearch_queue/queue/number_of_job_to_run';

    const XML_PATH_IMAGE_WIDTH                 = 'algoliasearch_image/image/width';
    const XML_PATH_IMAGE_HEIGHT                = 'algoliasearch_image/image/height';
    const XML_PATH_IMAGE_TYPE                  = 'algoliasearch_image/image/type';

    const NUMBER_OF_ELEMENT_BY_PAGE            = 'algoliasearch_advanced/advanced/number_of_element_by_page';
    const REMOVE_IF_NO_RESULT                  = 'algoliasearch_advanced/advanced/remove_words_if_no_result';
    const PARTIAL_UPDATES                      = 'algoliasearch_advanced/advanced/partial_update';
    const CUSTOMER_GROUPS_ENABLE               = 'algoliasearch_advanced/advanced/customer_groups_enable';
    const MAKE_SEO_REQUEST                     = 'algoliasearch_advanced/advanced/make_seo_request';
    const REMOVE_BRANDING                      = 'algoliasearch_advanced/advanced/remove_branding';
    const AUTOCOMPLETE_SELECTOR                = 'algoliasearch_advanced/advanced/autocomplete_selector';

    const SHOW_OUT_OF_STOCK                    = 'cataloginventory/options/show_out_of_stock';

    protected $_productTypeMap = array();

    protected $configInterface;
    protected $objectManager;
    protected $currency;
    protected $storeManager;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $configInterface,
                                \Magento\Framework\ObjectManagerInterface $objectManager,
                                StoreManagerInterface $storeManager,
                                Currency $currency
    )
    {
        $this->objectManager = $objectManager;
        $this->configInterface = $configInterface;
        $this->currency = $currency;
        $this->storeManager = $storeManager;
    }

    public function isDefaultSelector($storeId = null)
    {
        return '.algolia-search-input' === $this->getAutocompleteSelector($storeId);
    }

    public function getAutocompleteSelector($storeId = null)
    {
        return $this->configInterface->getValue(self::AUTOCOMPLETE_SELECTOR, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getNumberOfQueriesSuggestions($storeId = null)
    {
        return $this->configInterface->getValue(self::NB_OF_QUERIES_SUGGESTIONS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getNumberOfProductsSuggestions($storeId = null)
    {
        return $this->configInterface->getValue(self::NB_OF_PRODUCTS_SUGGESTIONS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getNumberOfCategoriesSuggestions($storeId = null)
    {
        return $this->configInterface->getValue(self::NB_OF_CATEGORIES_SUGGESTIONS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function showSuggestionsOnNoResultsPage($storeId = null)
    {
        return $this->configInterface->getValue(self::SHOW_SUGGESTIONS_NO_RESULTS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isEnabledFrontEnd($storeId = null)
    {
        // Frontend = Backend + Frontent
        return (bool) $this->configInterface->getValue(self::ENABLE_BACKEND, ScopeInterface::SCOPE_STORE, $storeId) && (bool) $this->configInterface->getValue(self::ENABLE_FRONTEND, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isEnabledBackend($storeId = null)
    {
        return $this->configInterface->getValue(self::ENABLE_BACKEND, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function makeSeoRequest($storeId = null)
    {
        return $this->configInterface->getValue(self::MAKE_SEO_REQUEST, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isLoggingEnabled($storeId = null)
    {
        return $this->configInterface->getValue(self::LOGGING_ENABLED, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getShowOutOfStock($storeId = null)
    {
        return $this->configInterface->getValue(self::SHOW_OUT_OF_STOCK, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function noProcess($storeId = null)
    {
        return $this->configInterface->getValue(self::NO_PROCESS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getImageWidth($storeId = null)
    {
        $imageWidth = $this->configInterface->getValue(self::XML_PATH_IMAGE_WIDTH, ScopeInterface::SCOPE_STORE, $storeId);
        if(empty($imageWidth)) {
            return null;
        }
        return $imageWidth;
    }

    public function getImageHeight($storeId = null)
    {
        $imageHeight = $this->configInterface->getValue(self::XML_PATH_IMAGE_HEIGHT, ScopeInterface::SCOPE_STORE, $storeId);
        if(empty($imageHeight)) {
            return null;
        }
        return $imageHeight;
    }

    public function getImageType($storeId = null)
    {
        return $this->configInterface->getValue(self::XML_PATH_IMAGE_TYPE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isCustomerGroupsEnabled($storeId = null)
    {
        return $this->configInterface->getValue(self::CUSTOMER_GROUPS_ENABLE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isPartialUpdateEnabled($storeId = null)
    {
        return $this->configInterface->getValue(self::PARTIAL_UPDATES, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getAutocompleteSections($storeId = null)
    {
        $attrs = unserialize($this->configInterface->getValue(self::AUTOCOMPLETE_SECTIONS, ScopeInterface::SCOPE_STORE, $storeId));

        if (is_array($attrs))
            return array_values($attrs);

        return array();
    }

    public function getNumberOfQuerySuggestions($storeId = null)
    {
        return $this->configInterface->getValue(self::NUMBER_QUERY_SUGGESTIONS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getMinPopularity($storeId = null)
    {
        return $this->configInterface->getValue(self::MIN_POPULARITY, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getMinNumberOfResults($storeId = null)
    {
        return $this->configInterface->getValue(self::MIN_NUMBER_OF_RESULTS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isAddToCartEnable($storeId = null)
    {
        return $this->configInterface->getValue(self::XML_ADD_TO_CART_ENABLE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isRemoveBranding($storeId = null)
    {
        return $this->configInterface->getValue(self::REMOVE_BRANDING, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getMaxValuesPerFacet($storeId = null)
    {
        return $this->configInterface->getValue(self::MAX_VALUES_PER_FACET, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getNumberOfElementByPage($storeId = null)
    {
        return $this->configInterface->getValue(self::NUMBER_OF_ELEMENT_BY_PAGE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getNumberOfJobToRun($storeId = null)
    {
        return $this->configInterface->getValue(self::NUMBER_OF_JOB_TO_RUN, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isQueueActive($storeId = null)
    {
        return $this->configInterface->getValue(self::IS_ACTIVE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getRemoveWordsIfNoResult($storeId = NULL)
    {
        return $this->configInterface->getValue(self::REMOVE_IF_NO_RESULT, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getNumberOfProductResults($storeId = NULL)
    {
        return (int) $this->configInterface->getValue(self::NUMBER_OF_PRODUCT_RESULTS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getResultsLimit($storeId = NULL)
    {
        return $this->configInterface->getValue(self::RESULTS_LIMIT, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isPopupEnabled($storeId = NULL)
    {
        return $this->configInterface->getValue(self::IS_POPUP_ENABLED, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function replaceCategories($storeId = NULL)
    {
        return $this->configInterface->getValue(self::REPLACE_CATEGORIES, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isAutoCompleteEnabled($storeId = NULL)
    {
        return $this->configInterface->getValue(self::IS_POPUP_ENABLED, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isInstantEnabled($storeId = NULL)
    {
        return $this->configInterface->getValue(self::IS_INSTANT_ENABLED, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getInstantSelector($storeId = NULL)
    {
        return $this->configInterface->getValue(self::INSTANT_SELECTOR, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getExcludedPages($storeId = NULL)
    {
        $attrs = unserialize($this->configInterface->getValue(self::EXCLUDED_PAGES, ScopeInterface::SCOPE_STORE, $storeId));

        if (is_array($attrs))
            return $attrs;

        return array();
    }

    public function getSortingIndices($storeId = null)
    {
        $productHelper = $this->objectManager->create('Algolia\AlgoliaSearch\Helper\Entity\ProductHelper');

        $attrs = unserialize($this->configInterface->getValue(self::SORTING_INDICES, ScopeInterface::SCOPE_STORE, $storeId));

        if ($storeId === null) {
            /** @var \Magento\Customer\Model\Session $customerSession */
            $customerSession = $this->objectManager->create('Magento\Customer\Model\Session');
            $group_id = $customerSession->getCustomerGroupId();

            foreach ($attrs as &$attr) {
                if ($this->isCustomerGroupsEnabled($storeId)) {
                    if (strpos($attr['attribute'], 'price') !== false) {
                        $suffix_index_name = 'group_' . $group_id;

                        $attr['name'] = $productHelper->getIndexName($storeId) . '_' . $attr['attribute'] . '_' .$suffix_index_name.'_'.$attr['sort'];
                    }
                    else {
                        $attr['name'] = $productHelper->getIndexName($storeId). '_' .$attr['attribute'] . '_'.$attr['sort'];
                    }
                }
                else {
                    if (strpos($attr['attribute'], 'price') !== false) {
                        $attr['name'] = $productHelper->getIndexName($storeId). '_' .$attr['attribute'].'_' . 'default' . '_'.$attr['sort'];
                    }
                    else {
                        $attr['name'] = $productHelper->getIndexName($storeId). '_' .$attr['attribute'] . '_'.$attr['sort'];
                    }
                }
            }
        }

        if (is_array($attrs)) {
            return $attrs;
        }

        return array();
    }

    public function getApplicationID($storeId = NULL)
    {
        return $this->configInterface->getValue(self::APPLICATION_ID, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getAPIKey($storeId = NULL)
    {
        return $this->configInterface->getValue(self::API_KEY, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getSearchOnlyAPIKey($storeId = NULL)
    {
        return $this->configInterface->getValue(self::SEARCH_ONLY_API_KEY, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getIndexPrefix($storeId = NULL)
    {
        return $this->configInterface->getValue(self::INDEX_PREFIX, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getCategoryAdditionalAttributes($storeId = NULL)
    {
        $attrs = unserialize($this->configInterface->getValue(self::CATEGORY_ATTRIBUTES, ScopeInterface::SCOPE_STORE, $storeId));

        if (is_array($attrs))
            return $attrs;

        return array();
    }

    public function getProductAdditionalAttributes($storeId = NULL)
    {
        $attrs = unserialize($this->configInterface->getValue(self::PRODUCT_ATTRIBUTES, ScopeInterface::SCOPE_STORE, $storeId));

        if (is_array($attrs))
            return $attrs;

        return array();
    }

    public function getFacets($storeId = NULL)
    {
        $attrs = unserialize($this->configInterface->getValue(self::FACETS, ScopeInterface::SCOPE_STORE, $storeId));

        foreach ($attrs as &$attr)
            if ($attr['type'] == 'other')
                $attr['type'] = $attr['other_type'];

        if (is_array($attrs))
            return array_values($attrs);

        return array();
    }

    public function getCategoryCustomRanking($storeId = NULL)
    {
        $attrs = unserialize($this->configInterface->getValue(self::CATEGORY_CUSTOM_RANKING, ScopeInterface::SCOPE_STORE, $storeId));

        if (is_array($attrs))
            return $attrs;

        return array();
    }

    public function getProductCustomRanking($storeId = NULL)
    {
        $attrs = unserialize($this->configInterface->getValue(self::PRODUCT_CUSTOM_RANKING, ScopeInterface::SCOPE_STORE, $storeId));

        if (is_array($attrs))
            return $attrs;

        return array();
    }

    public function getCurrency($storeId = NULL)
    {
        $currencySymbol = $this->currency->getCurrency($this->storeManager->getStore()->getCurrentCurrencyCode())->getSymbol();

        return $currencySymbol;
    }

    public function getPopularQueries($storeId = null)
    {
        if ($storeId === null) {
            $storeId = $this->storeManager->getStore()->getId();
        }

        $suggestion_helper = $this->objectManager->create('Algolia\AlgoliaSearch\Helper\Entity\SuggestionHelper');
        $popularQueries = $suggestion_helper->getPopularQueries($storeId);

        return $popularQueries;
    }
}