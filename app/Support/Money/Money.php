<?php

declare(strict_types=1);

namespace App\Support\Money;

use Money\Currencies\ISOCurrencies;
use Money\Currency as CurrencyCore;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money as MoneyCore;
use Money\Parser\DecimalMoneyParser;
use Str;

class Money
{
    const ROUND_HALF_UP = MoneyCore::ROUND_HALF_UP;

    const ROUND_HALF_DOWN = MoneyCore::ROUND_HALF_DOWN;

    const ROUND_HALF_EVEN = MoneyCore::ROUND_HALF_EVEN;

    const ROUND_HALF_ODD = MoneyCore::ROUND_HALF_ODD;

    const ROUND_UP = MoneyCore::ROUND_UP;

    const ROUND_DOWN = MoneyCore::ROUND_DOWN;

    const ROUND_HALF_POSITIVE_INFINITY = MoneyCore::ROUND_HALF_POSITIVE_INFINITY;

    const ROUND_HALF_NEGATIVE_INFINITY = MoneyCore::ROUND_HALF_NEGATIVE_INFINITY;

    /** @var MoneyCore */
    protected $core;

    /**
     * Money constructor.
     * @param MoneyCore $money
     */
    public function __construct(MoneyCore $money)
    {
        $this->core = $money;
    }

    /**
     * @param string $currencyCode
     * @return CurrencyCore
     */
    protected static function getCurrencyCoreByCode(string $currencyCode) : CurrencyCore
    {
        return new CurrencyCore(\mb_strtoupper($currencyCode));
    }

    /**
     * @param float $value
     * @return Money
     */
    public static function fromValue(float $value) : self
    {
        $currencyCore = self::getCurrencyCoreByCode('rub');

        return new self(
            (new DecimalMoneyParser(new ISOCurrencies()))
                ->parse((string) $value, $currencyCore)
        );
    }

    /**
     * @param int $amount
     * @return Money
     */
    public static function fromAmount(int $amount) : self
    {
        $currencyCore = self::getCurrencyCoreByCode('rub');

        return new self(
            new MoneyCore($amount, $currencyCore)
        );
    }

    /**
     * @return int
     */
    public function getAmount() : int
    {
        return (int) $this->core->getAmount();
    }

    /**
     * @param bool $absolute
     * @return float
     */
    public function getValue(bool $absolute = true) : float
    {
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return (float) $formatter->format(
            $absolute
                ? $this->core->absolute()
                : $this->core
        );
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return (string) $this->getValue();
    }

    /**
     * @return string
     */
    public function getCurrencyCode() : string
    {
        return \mb_strtolower($this->core->getCurrency()->getCode());
    }

    /**
     * @return MoneyCore
     */
    private function core() : MoneyCore
    {
        return $this->core;
    }

    /**
     * @param Money $money
     * @return Money
     */
    public function add(Money $money) : self
    {
        return new self($this->core->add($money->core()));
    }

    /**
     * @param Money $money
     * @return Money
     */
    public function substract(Money $money) : self
    {
        return new self($this->core->subtract($money->core()));
    }

    /**
     * @param float|int|string $by
     * @param int $roundingMode
     * @return Money
     */
    public function divide($by, int $roundingMode = self::ROUND_HALF_UP) : self
    {
        return new self($this->core->divide($by, $roundingMode));
    }

    /**
     * @param float|int|string $by
     * @param int $roundingMode
     * @return Money
     */
    public function multiply($by, int $roundingMode = self::ROUND_HALF_UP) : self
    {
        return new self($this->core->multiply($by, $roundingMode));
    }

    /**
     * @param Money $money
     * @param bool $allowEqual
     * @return bool
     */
    public function greaterThan(Money $money, bool $allowEqual = false) : bool
    {
        return $allowEqual
            ? $this->core->greaterThan($money->core())
            : $this->core->greaterThanOrEqual($money->core());
    }

    /**
     * @param Money $money
     * @param bool $allowEqual
     * @return bool
     */
    public function lessThan(Money $money, bool $allowEqual = false) : bool
    {
        return $allowEqual
            ? $this->core->lessThan($money->core())
            : $this->core->lessThanOrEqual($money->core());
    }

    /**
     * @param bool $withCurrency
     * @return string
     */
    public function getFormattedValue(bool $withCurrency = false) : string
    {
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());
        $formattedValue = (string) $formatter->format($this->core);

        if (Str::endsWith($formattedValue, ['.00', ',00'])) {
            $formattedValue = \mb_substr($formattedValue, 0, -3);
        }

        if ($withCurrency) {
            $formattedValue = "$formattedValue {$this->getCurrencySymbol()}";
        }

        return $formattedValue;
    }

    /**
     * @return string
     */
    public function getCurrencySymbol() : string
    {
        return 'руб.';
    }
}
