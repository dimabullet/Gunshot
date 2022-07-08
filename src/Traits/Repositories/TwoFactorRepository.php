<?php

namespace BulletDigitalSolutions\Gunshot\Traits\Repositories;

use App\Entities\User;
use Carbon\Carbon;
use Laravel\Fortify\RecoveryCode;

trait TwoFactorRepository
{
    /**
     * @param $user
     * @param $secret
     * @param $codes
     * @return User
     */
    public function enableTwoFactor($user, $secret, $codes)
    {
        $user->setTwoFactorSecret($secret);
        $user->setTwoFactorRecoveryCodes($codes);

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    /**
     * @param $user
     * @return mixed
     */
    public function disableTwoFactor($user)
    {
        $user->setTwoFactorSecret(null);
        $user->setTwoFactorRecoveryCodes(null);
        $user->setTwoFactorConfirmedAt(null);

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    /**
     * @param $user
     * @return mixed
     */
    public function confirmTwoFactor($user)
    {
        $user->setTwoFactorConfirmedAt(Carbon::now());

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    /**
     * @param $user
     * @param $codes
     * @return mixed
     */
    public function updateRecoveryCodes($user, $codes)
    {
        $user->setTwoFactorRecoveryCodes($codes);

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    /**
     * @param $user
     * @param $code
     * @return mixed
     */
    public function replaceRecoveryCode($user, $code)
    {
        $codes = encrypt(str_replace(
            $code,
            RecoveryCode::generate(),
            decrypt($user->getTwoFactorRecoveryCodes())
        ));

        $user->setTwoFactorRecoveryCodes($codes);

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }
}
