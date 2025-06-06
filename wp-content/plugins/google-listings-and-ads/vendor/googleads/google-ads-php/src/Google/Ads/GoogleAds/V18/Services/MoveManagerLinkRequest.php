<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v18/services/customer_manager_link_service.proto

namespace Google\Ads\GoogleAds\V18\Services;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Request message for
 * [CustomerManagerLinkService.MoveManagerLink][google.ads.googleads.v18.services.CustomerManagerLinkService.MoveManagerLink].
 *
 * Generated from protobuf message <code>google.ads.googleads.v18.services.MoveManagerLinkRequest</code>
 */
class MoveManagerLinkRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. The ID of the client customer that is being moved.
     *
     * Generated from protobuf field <code>string customer_id = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    protected $customer_id = '';
    /**
     * Required. The resource name of the previous CustomerManagerLink.
     * The resource name has the form:
     * `customers/{customer_id}/customerManagerLinks/{manager_customer_id}~{manager_link_id}`
     *
     * Generated from protobuf field <code>string previous_customer_manager_link = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    protected $previous_customer_manager_link = '';
    /**
     * Required. The resource name of the new manager customer that the client
     * wants to move to. Customer resource names have the format:
     * "customers/{customer_id}"
     *
     * Generated from protobuf field <code>string new_manager = 3 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    protected $new_manager = '';
    /**
     * If true, the request is validated but not executed. Only errors are
     * returned, not results.
     *
     * Generated from protobuf field <code>bool validate_only = 4;</code>
     */
    protected $validate_only = false;

    /**
     * @param string $customerId                  Required. The ID of the client customer that is being moved.
     * @param string $previousCustomerManagerLink Required. The resource name of the previous CustomerManagerLink.
     *                                            The resource name has the form:
     *                                            `customers/{customer_id}/customerManagerLinks/{manager_customer_id}~{manager_link_id}`
     * @param string $newManager                  Required. The resource name of the new manager customer that the client
     *                                            wants to move to. Customer resource names have the format:
     *                                            "customers/{customer_id}"
     *
     * @return \Google\Ads\GoogleAds\V18\Services\MoveManagerLinkRequest
     *
     * @experimental
     */
    public static function build(string $customerId, string $previousCustomerManagerLink, string $newManager): self
    {
        return (new self())
            ->setCustomerId($customerId)
            ->setPreviousCustomerManagerLink($previousCustomerManagerLink)
            ->setNewManager($newManager);
    }

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $customer_id
     *           Required. The ID of the client customer that is being moved.
     *     @type string $previous_customer_manager_link
     *           Required. The resource name of the previous CustomerManagerLink.
     *           The resource name has the form:
     *           `customers/{customer_id}/customerManagerLinks/{manager_customer_id}~{manager_link_id}`
     *     @type string $new_manager
     *           Required. The resource name of the new manager customer that the client
     *           wants to move to. Customer resource names have the format:
     *           "customers/{customer_id}"
     *     @type bool $validate_only
     *           If true, the request is validated but not executed. Only errors are
     *           returned, not results.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Ads\GoogleAds\V18\Services\CustomerManagerLinkService::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. The ID of the client customer that is being moved.
     *
     * Generated from protobuf field <code>string customer_id = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * Required. The ID of the client customer that is being moved.
     *
     * Generated from protobuf field <code>string customer_id = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param string $var
     * @return $this
     */
    public function setCustomerId($var)
    {
        GPBUtil::checkString($var, True);
        $this->customer_id = $var;

        return $this;
    }

    /**
     * Required. The resource name of the previous CustomerManagerLink.
     * The resource name has the form:
     * `customers/{customer_id}/customerManagerLinks/{manager_customer_id}~{manager_link_id}`
     *
     * Generated from protobuf field <code>string previous_customer_manager_link = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return string
     */
    public function getPreviousCustomerManagerLink()
    {
        return $this->previous_customer_manager_link;
    }

    /**
     * Required. The resource name of the previous CustomerManagerLink.
     * The resource name has the form:
     * `customers/{customer_id}/customerManagerLinks/{manager_customer_id}~{manager_link_id}`
     *
     * Generated from protobuf field <code>string previous_customer_manager_link = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param string $var
     * @return $this
     */
    public function setPreviousCustomerManagerLink($var)
    {
        GPBUtil::checkString($var, True);
        $this->previous_customer_manager_link = $var;

        return $this;
    }

    /**
     * Required. The resource name of the new manager customer that the client
     * wants to move to. Customer resource names have the format:
     * "customers/{customer_id}"
     *
     * Generated from protobuf field <code>string new_manager = 3 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return string
     */
    public function getNewManager()
    {
        return $this->new_manager;
    }

    /**
     * Required. The resource name of the new manager customer that the client
     * wants to move to. Customer resource names have the format:
     * "customers/{customer_id}"
     *
     * Generated from protobuf field <code>string new_manager = 3 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param string $var
     * @return $this
     */
    public function setNewManager($var)
    {
        GPBUtil::checkString($var, True);
        $this->new_manager = $var;

        return $this;
    }

    /**
     * If true, the request is validated but not executed. Only errors are
     * returned, not results.
     *
     * Generated from protobuf field <code>bool validate_only = 4;</code>
     * @return bool
     */
    public function getValidateOnly()
    {
        return $this->validate_only;
    }

    /**
     * If true, the request is validated but not executed. Only errors are
     * returned, not results.
     *
     * Generated from protobuf field <code>bool validate_only = 4;</code>
     * @param bool $var
     * @return $this
     */
    public function setValidateOnly($var)
    {
        GPBUtil::checkBool($var);
        $this->validate_only = $var;

        return $this;
    }

}

