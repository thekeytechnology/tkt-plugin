<?php

function tkAddPmsFilters(TkTemplate $tkTwig)
{
    $tkTwig->addFilter("pmsGetMember", function ($item) {
        return tkWpApplyWithId($item, function () {
        }, function () {
        }, function ($id) {
            return pms_get_member($id);
        });
    });

    $tkTwig->addFilter("pmsGetUserSubscriptions", function ($item) {
        $member = tkWpApplyWithId($item, function () {
        }, function () {
        }, function ($id) {
            return pms_get_member($id);
        });


        $member_subscriptions = array();
        if (!empty($member->subscriptions)) {
            foreach ($member->subscriptions as $subscription) {
                if (!empty($subscription['status']) && ($subscription['status'] == 'active' || $subscription['status'] == 'canceled') && time() <= strtotime($subscription['expiration_date'])) {
                    $member_subscriptions[] = $subscription['subscription_plan_id'];
                }
            }
        }
        return $member_subscriptions;
    });

    $tkTwig->addFilter("pmsGetSubscriptionParents", function ($subscriptionId) {

        $subscriptions = array();
        $subscriptions[] = $subscriptionId;
        $subObject = get_post($subscriptionId);

        while ($subObject->post_parent != 0) {
            $subscriptions[] = $subObject->post_parent;
            $subObject = get_post($subObject->post_parent);
        }
        return $subscriptions;


    });

    $tkTwig->addFilter("pmsIsFreeSubscription", function ($subscription) {
        return pms_get_subscription_plan($subscription['ID'])->price == 0;
    });
}
