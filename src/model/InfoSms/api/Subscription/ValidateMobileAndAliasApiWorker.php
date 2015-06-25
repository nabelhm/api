<?php

namespace Muchacuba\InfoSms\Subscription;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class ValidateMobileAndAliasApiWorker
{
    /**
     * @var ValidateMobileAndAliasInternalWorker
     */
    private $validateMobileAndAliasInternalWorker;

    /**
     * @param ValidateMobileAndAliasInternalWorker $validateMobileAndAliasInternalWorker
     *
     * @Di\InjectParams({
     *     "validateMobileAndAliasInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.validate_mobile_and_alias_internal_worker")
     * })
     */
    function __construct(
        ValidateMobileAndAliasInternalWorker $validateMobileAndAliasInternalWorker
    )
    {
        $this->validateMobileAndAliasInternalWorker = $validateMobileAndAliasInternalWorker;
    }

    /**
     * Validates given mobile and alias.
     *
     * @param string $mobile
     * @param string $alias
     *
     * @throws InvalidMobileApiException
     * @throws BlankAliasApiException
     * @throws ExistentMobileApiException
     */
    public function validate($mobile, $alias)
    {
        try {
            $this->validateMobileAndAliasInternalWorker->validate($mobile, $alias);
        } catch (InvalidMobileInternalException $e) {
            throw new InvalidMobileApiException();
        } catch (BlankAliasInternalException $e) {
            throw new BlankAliasApiException();
        } catch (ExistentMobileInternalException $e) {
            throw new ExistentMobileApiException();
        }
    }
}
