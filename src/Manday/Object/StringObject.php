<?php

namespace Manday\Object;

/**
 * Helps string handling and manipulation.
 * 
 * This should not be used for simple string processing.
 *
 * @author Manda Yugana
 */
class StringObject
{
    /**
     * String primitive data.
     * 
     * @var string
     */
    protected $string;
    
    /**
     * Object constructor.
     * 
     * @param string $string The primitive data.
     */
    public function __construct(string $string)
    {
        $this->string = $string;
    }
    
    /**
     * Converts object to string.
     * 
     * @return string
     */
    public function __toString(): string
    {
        return $this->string;
    }
    
    /**
     * Returns length of the string.
     * 
     * @return int String length.
     */
    public function length(): int
    {
        return strlen($this->string);
    }
    
    /**
     * Strips supplied characters from the beginning and from the end of
     * the string.
     * 
     * @param string $chars Characters to be stripped from the
     * string.
     * @return \Manday\Object\StringObject Trimmed string.
     */
    public function trim(string $chars): StringObject
    {
        return $this->newString(trim($this->string, $chars));
    }
    
    /**
     * Strips supplied characters from the beginning of the string.
     * 
     * @param string $chars Characters to be stripped from the
     * string.
     * @return \Manday\Object\StringObject Trimmed string.
     */
    public function ltrim(string $chars): StringObject
    {
        return $this->newString(ltrim($this->string, $chars));
    }
    
    /**
     * Strips supplied characters from the end of the string.
     * 
     * @param string $chars Characters to be stripped from the
     * string.
     * @return \Manday\Object\StringObject Trimmed string.
     */
    public function rtrim(string $chars): StringObject
    {
        return $this->newString(rtrim($this->string, $chars));
    }
    
    /**
     * Adds another string to the end of the string.
     * 
     * @param string $other String to append.
     * @return \Manday\Object\StringObject String that represents the string
     * followed by argument string.
     */
    public function append(string $other): StringObject
    {
        return $this->newString($this->string . $other);
    }

    /**
     * Adds another string to the beginning of the string.
     * 
     * @param string $other String to prepend.
     * @return \Manday\Object\StringObject String that represents argument
     * string followed by the string.
     */    
    public function prepend(string $other): StringObject
    {
        return $this->newString($other . $this->string);
    }
    
    /**
     * Converts the string to uppercase.
     * 
     * @return \Manday\Object\StringObject Uppercased string.
     */
    public function upperCase(): StringObject
    {
        return $this->newString(strtoupper($this->string));
    }
    
    /**
     * Converts the string to lowercase.
     * 
     * @return \Manday\Object\StringObject Lowercased string.
     */
    public function lowerCase(): StringObject
    {
        return $this->newString(strtolower($this->string));
    }
    
    /**
     * Reverse all characters in the string. First character becomes last
     * character, and vice versa.
     * 
     * @return \Manday\Object\StringObject Reversed string.
     */
    public function reverse(): StringObject
    {
        return $this->newString(strrev($this->string));
    }
    
    /**
     * Gets a portion of the string. Returned string will start at beginning
     * index until one character before the end index.
     * 
     * Example:
     * 
     * Character indexes of string <code>Baleendah</code> is:
     * 
     * <code>
     * B    a    l    e    e    n    d    a    h
     * 
     * 0    1    2    3    4    5    6    7    8
     * </code>
     * 
     * Example in code:
     * 
     * <code>
     * $string = new StringObject('Baleendah');
     * 
     * echo $string->subString(0, 6); // Baleen
     * </code>
     * 
     * @param int $beginIndex The beginning index.
     * @param int $endIndex The end index.
     * @return \Manday\Object\StringObject The substring.
     * @throws \OutOfBoundsException If <code>beginIndex</code> or
     * <code>endIndex</code> is less than <code>0</code>. Or, If
     * <code>beginIndex</code> is greater than or equals the length of the
     * string. Or, if <code>endIndex</code> is greater than the length of the
     * string. Or, if <code>beginIndex</code> is greater than or equals
     * <code>endIndex</code>.
     */
    public function subString(int $beginIndex, int $endIndex = null): StringObject
    {        
        if ($endIndex === null) {
            $endIndex = $this->length();
        }
        
        if ($beginIndex < 0 || $beginIndex >= $this->length()) {
            throw new \OutOfBoundsException(
                sprintf('Begin index must be integer starts from 0 to %d', $this->length())
            );
        }
        
        if ($endIndex < 0 || $endIndex > $this->length()) {
            throw new \OutOfBoundsException(
                sprintf('End index must be integer starts from 0 to %d', $this->length())
            );
        }
        
        if ($beginIndex >= $endIndex) {
            throw new \OutOfBoundsException(
                'End index must be greater than begin index'
            );
        }
        
        return $this->newString(
            substr($this->string, $beginIndex, $endIndex - $beginIndex)
        );
    }
    
    /**
     * Replaces substrings with another strings.
     * 
     * Example:
     * <code>
     * $string = new StringObject('Baleendah');
     * 
     * echo $string->replace(['endah' => 'agung']); // Baleagung
     * </code>
     * 
     * @param array $searchAndReplace Associative array which keys are
     * substrings to be replaced and the values are the replacements.
     * @return \Manday\Object\StringObject Replaced string.
     * @throws \TypeError If any of the array element is not string.
     */
    public function replace(array $searchAndReplace): StringObject
    {
        $this->assertStringArray($searchAndReplace);
        return $this->newString(strtr($this->string, $searchAndReplace));
    }
    
    /**
     * Splits strings on boundary formed by the string delimiter.
     * 
     * @param string $delimiter The boundary string.
     * @param int $limit Limit of amount of returned strings.
     * @return array Strings created by splitting the string.
     * @throws \OutOfBoundsException If limit is not positive integer.
     */
    public function split(string $delimiter, int $limit = \PHP_INT_MAX): array
    {
        if ($limit < 1) {
            throw new \OutOfBoundsException(
                sprintf('Limit is not positive integer: %s', $limit)
            );
        }
        $strings = explode($delimiter, $this->string, $limit);
        $return = [];
        foreach ($strings as $string) {
            $return[] = $this->newString($string);
        }
        return $return;
    }
    
    /**
     * Checks whether the string contains supplied substring or not.
     * 
     * @param string $subString Substring to search.
     * @return bool True if the string contains supplied substring. False
     * otherwise.
     */
    public function contains(string $subString): bool
    {
        return (strpos($this->string, $subString) !== false);
    }
    
    /**
     * Checks whether the string starts with supplied substring or not.
     * 
     * @param string $subString Substring to search.
     * @return bool True if the string starts with supplied substring. False
     * otherwise.
     */
    public function startsWith(string $subString): bool
    {
        return (strpos($this->string, (string) $subString) === 0);
    }
    
    /**
     * Checks whether the string ends with supplied substring or not.
     * 
     * @param string $subString Substring to search.
     * @return bool True if the string ends with supplied substring. False
     * otherwise.
     */
    public function endsWith(string $subString): bool
    {
        return $this->reverse()->startsWith(strrev($subString));
    }
    
    /**
     * Creates a new string object.
     * 
     * @param string $string Primitive data.
     * @return \Manday\Object\StringObject New string object.
     */
    protected function newString(string $string): StringObject
    {
        return new static($string);
    }
    
    /**
     * Ensures all elements in supplied array are (convertable to) strings.
     * 
     * @param array $value Array to check.
     * @return void
     * @throws \TypeError If any of the array element is not string.
     */
    protected function assertStringArray(array $value): void
    {
        $assertString = function (string $ignoredArgument) {};
        foreach ($value as $v) {
            $assertString($v);
        }
    }
}
