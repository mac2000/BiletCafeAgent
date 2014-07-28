<?php
namespace BiletCafe\Entities;

use BiletCafe\Tickets\Train;
use mac\bit\Bit;

trait Classes
{
    use Bit;

    /**
     * @var int
     */
    public $classes;

    protected function isClassIncluded($class = Train::ALL)
    {
        return $this->isFlagSet($class, $this->classes);
    }

    protected function includeClass($class = Train::ALL)
    {
        $this->setFlag($class, $this->classes);
    }

    protected function excludeClass($class = Train::ALL)
    {
        $this->unsetFlag($class, $this->classes);
    }

    protected function toggleClass($class = Train::ALL)
    {
        $this->toggleFlag($class, $this->classes);
    }

    public function isFirstClassIncluded()
    {
        return $this->isClassIncluded(Train::FIRST);
    }

    public function isSecondClassIncluded()
    {
        return $this->isClassIncluded(Train::SECOND);
    }

    public function isThirdClassIncluded()
    {
        return $this->isClassIncluded(Train::THIRD);
    }

    public function isReservedClassIncluded()
    {
        return $this->isClassIncluded(Train::RESERVED);
    }

    public function isNonReservedClassIncluded()
    {
        return $this->isClassIncluded(Train::NON_RESERVED);
    }

    public function isComfortableClassIncluded()
    {
        return $this->isClassIncluded(Train::COMFORTABLE);
    }

    public function includeFirstClass()
    {
        $this->includeClass(Train::FIRST);
    }

    public function includeSecondClass()
    {
        $this->includeClass(Train::SECOND);
    }

    public function includeThirdClass()
    {
        $this->includeClass(Train::THIRD);
    }

    public function includeReservedClass()
    {
        $this->includeClass(Train::RESERVED);
    }

    public function includeNonReservedClass()
    {
        $this->includeClass(Train::NON_RESERVED);
    }

    public function includeComfortableClass()
    {
        $this->includeClass(Train::COMFORTABLE);
    }

    public function excludeFirstClass()
    {
        $this->excludeClass(Train::FIRST);
    }

    public function excludeSecondClass()
    {
        $this->excludeClass(Train::SECOND);
    }

    public function excludeThirdClass()
    {
        $this->excludeClass(Train::THIRD);
    }

    public function excludeReservedClass()
    {
        $this->excludeClass(Train::RESERVED);
    }

    public function excludeNonReservedClass()
    {
        $this->excludeClass(Train::NON_RESERVED);
    }

    public function excludeComfortableClass()
    {
        $this->excludeClass(Train::COMFORTABLE);
    }

    public function toggleFirstClass()
    {
        $this->toggleClass(Train::FIRST);
    }

    public function toggleSecondClass()
    {
        $this->toggleClass(Train::SECOND);
    }

    public function toggleThirdClass()
    {
        $this->toggleClass(Train::THIRD);
    }

    public function toggleReservedClass()
    {
        $this->toggleClass(Train::RESERVED);
    }

    public function toggleNonReservedClass()
    {
        $this->toggleClass(Train::NON_RESERVED);
    }

    public function toggleComfortableClass()
    {
        $this->toggleClass(Train::COMFORTABLE);
    }
}
