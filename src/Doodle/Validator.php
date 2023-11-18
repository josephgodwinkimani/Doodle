<?php

declare(strict_types=1);

/*
 * This file is part of the Doodle package.
 *
 * (c) Godwin Kimani <josephgodwinke@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Doodle;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * A Class to validate values against constraints following the JSR-303 Bean Validation specification
 */

class Validator
{

    protected ValidatorInterface $validator;

    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    /**
     * Validates if a value is of a specific data type
     *
     * Checks if its not empty, and is of a specific data type,
     *
     *
     * @param mixed        $value Value to be validated
     * @param array|string $type  Type or collection of types allowed for the given value. Default is "string". e.g. bool, boolean, int, integer, long, float, double, real, numeric, string, scalar, array, iterable, countable, callable, object, resource, null, alnum, alpha, cntrl, digit, graph, lower, print, punct, space, upper, xdigit
     *
     *                            Example:
     *                            ```
     *                            $validator = new Doodle\Validator();
     *                            $violations = $validator->isDataType("234", "int");  echo $violations;
     *                            //   234:
     *                            The value "234" is not a valid int. (code ba785a8c-82cb-4283-967c-3cf342181b40)
     *                            ```
     *
     * @return object
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function isDataType($value, $type = "string")
    {
        return $this->validator->validate($value, [
            new Length(["min" => 1]),
            new NotBlank(),
            new Assert\Type([
                "type" => $type,
                "message" => "The value {{ value }} is not a valid {{ type }}.",
            ]),
        ]);
    }

    /**
     * Validates if a value only contains only alphabetic characters
     *
     * Checks if its not empty, contains alphabetic characters,
     * matches one or more occurences of any uppercase or
     * lowercase letter in the English alphabet,
     *
     *
     * @param mixed $value Value to be validated
     *
     *                     Example:
     *                     ```$validator = new Doodle\Validator();
     *                     $violations = $validator->isAlpha("$#2");
     *                     echo $violations;
     *
     *                     //   $#2: The value "$#2" should only contain special characters and should not be empty. (code de1e3db3-5ed4-4941-aae4-59f3667cc3a3)
     *
     * @return object
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function isAlpha($value)
    {
        return $this->validator->validate($value, [
            new Length(["min" => 1]),
            new NotBlank(),
            new Assert\Regex([
                "pattern" => '/^[a-z]+$/i',
                "htmlPattern" => "[a-zA-Z]+",
                "message" => "The value {{ value }} should only contain alphabetic characters and should not be empty.",
            ]),
        ]);
    }

    /**
     * Validates if a value only contains only special characters
     *
     * Checks if its not empty, contains special characters
     *
     *
     * @param mixed $value Value to be validated
     *
     *                     Example:
     *                     ```$validator = new Doodle\Validator();
     *                     $violations = $validator->isSpecial("fGT");
     *                     echo $violations;
     *
     *                     //   fGT: The value "fGT" should only contain special characters and should not be empty. (code de1e3db3-5ed4-4941-aae4-59f3667cc3a3)
     *
     * @return object
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function isSpecial($value)
    {
        return $this->validator->validate($value, [
            new Length(["min" => 1]),
            new NotBlank(),
            new Assert\Regex([
                "pattern" => '/^[^A-Za-z0-9\s]+$/',
                "htmlPattern" => "^[^A-Za-z0-9\s]+$",
                "message" => "The value {{ value }} should only contain special characters and should not be empty.",
            ]),
        ]);
    }

    /**
     * Validates if a value contains only numbers
     *
     * Checks if its not empty, contains only numbers
     *
     *
     * @param mixed $value Value to be validated
     *
     *                     Example:
     *                     ```$validator = new Doodle\Validator();
     *                     $violations = $validator->isNumber("$#!2");
     *                     echo $violations;
     *
     *                     //  $#!2: The value "$#!2" is not a valid number. (code de1e3db3-5ed4-4941-aae4-59f3667cc3a3)
     *                     ```
     *
     * @return object
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function isNumber($value)
    {
        return $this->validator->validate($value, [
            new Length(["min" => 1]),
            new NotBlank(),
            new Assert\Regex([
                "pattern" => '/^\d+$/',
                "htmlPattern" => "^\d+$",
                "message" => "The value {{ value }} is not a valid number.",
            ]),
        ]);
    }

    /**
     * Validates if a value contains only float
     *
     * Checks if its not empty, contains only float
     *
     *
     * @param mixed $value Value to be validated
     *
     *                     Example:
     *                     ```$validator = new Doodle\Validator();
     *                     $violations = $validator->isFloat("2.0@");
     *                     echo $violations;
     *
     *                     //  2.0@: The value "2.0@" is not a valid float. (code de1e3db3-5ed4-4941-aae4-59f3667cc3a3)
     *                     ```
     *
     * @return object
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function isFloat($value)
    {
        return $this->validator->validate($value, [
            new Length(["min" => 1]),
            new NotBlank(),
            new Assert\Regex([
                "pattern" => '/^\d+(\.\d+)?$/',
                "htmlPattern" => "^\d+(\.\d+)?$",
                "message" => "The value {{ value }} is not a valid float.",
            ]),
        ]);
    }

    /**
     * Validates if a value is a valid email address
     *
     * Checks if its not empty, contains valid email address
     *
     *
     * @param mixed $value Value to be validated
     *
     *                     Example:
     *                     ```$validator = new Doodle\Validator();
     *                     $violations = $validator->isEmail("fGT");
     *                     echo $violations;
     *
     *                     //  fGT: The email "fGT" is not a valid email. (code bd79c0ab-ddba-46cc-a703-a7a4b08de310)
     *                     ```
     *
     * @return object
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function isEmail($value)
    {
        return $this->validator->validate($value, [
            new Length(["min" => 1]),
            new NotBlank(),
            new Assert\Email([
                "message" => "The email {{ value }} is not a valid email.",
            ]),
        ]);
    }

    /**
     * Validates if a value is valid date
     *
     * Checks if its not empty and is valid date
     *
     *
     * @param mixed $value Value to be validated
     *                     Example:
     *                     ```$validator = new
     *                     Doodle\Validator();
     *                     $violations =
     *                     $validator->isDate("2023-05-08-09");
     *                     echo $violations; //
     *                     2023-05-08-09: The
     *                     value "2023-05-08-09"
     *                     is not a valid date.
     *                     (code
     *                     1a9da513-2640-4f84-9b6a-4d99dcddc628)
     *                     ```echo $violations; //
     *
     * @return object
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function isDate($value)
    {
        return $this->validator->validate($value, [
            new Length(["min" => 1]),
            new NotBlank(),
            new Assert\Date([
                "message" => "The value {{ value }} is not a valid date.",
            ]),
        ]);
    }

    /**
     * Validates if a value is valid datetime
     *
     * Checks if its not empty and is valid datetime
     *
     *
     * @param mixed  $value  Value to be validated
     * @param string $format Custom date format to validate against
     *
     *                       Example:
     *                       ```$validator = new Doodle\Validator();
     *                       $violations = $validator->isDateTime("2023-05-08-09 12:53:28", "Y-m-d H:i:s");
     *                       echo $violations;
     *
     *                       //  2023-05-08-09 12:53:28: The value "2023-05-08-09 12:53:28" is not a valid datetime. (code 1a9da513-2640-4f84-9b6a-4d99dcddc628)
     *                       ```
     *
     * @return object
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function isDateTime($value, $format)
    {
        return $this->validator->validate($value, [
            new Length(["min" => 1]),
            new NotBlank(),
            new Assert\DateTime([
                "format" => $format,
                "message" => "The value {{ value }} is not a valid datetime.",
            ]),
        ]);
    }

    /**
     * Validates if a value is valid JSON
     *
     * Checks if its not empty and is a valid JSON value
     *
     *
     * @param mixed $value Value to be validated
     *
     *                     Example:
     *                     ```$validator = new Doodle\Validator();
     *                     $violations = $validator->isJson('{"id":1,"name":"teddy"}');
     *                     echo $violations;
     *
     *                     //  {"id":1,"name":"teddy"}: The value "2023-05-08-09 12:53:28" does not have valid JSON syntax. (code 0789c8ad-2d2b-49a4-8356-e2ce63998504)
     *                     ```
     *
     * @return object
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function isJson($value)
    {
        return $this->validator->validate($value, [
            new Length(["min" => 1]),
            new NotBlank(),
            new Assert\Json([
                "message" => "The value {{ value }} does not have valid JSON syntax.",
            ]),
        ]);
    }

    /**
     * Validates if a value is valid Url
     *
     * Checks if its not empty and is a valid Url value
     *
     *
     * @param mixed $value     Value to be validated
     * @param array $protocols Array of Url schemes to validate against. Default is ["http", "https", "ftp"]
     * @param bool  $relative  Allow relative Url protocols to be validated. . Default is false
     *
     *                         Example:
     *                         ```
     *                         $validator = new Doodle\Validator();
     *                         $violations =$validator->isUrl("https://gty.com", ["ftps"]);
     *                         echo $violations;
     *                         //  https://gty.com: The value "https://gty.com" is is not a valid url.
     *
     * @return object
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function isUrl($value, $protocols = ["http", "https", "ftp"], $relative = false)
    {
        return $this->validator->validate($value, [
            new Length(["min" => 1]),
            new NotBlank(),
            new Assert\Url([
                "protocols" => $protocols,
                "relativeProtocol" => $relative,
                "message" => "The value {{ value }} is not a valid url.",
            ]),
        ]);
    }

    /**
     * Validates if a value is between some minimum and maximum value
     *
     * Checks if its not empty and is between some minimum and maximum value
     *
     *
     * @param mixed  $value   Value to be validated
     * @param int    $min     The minimum length value. Default is 1. e.g. 23
     * @param int    $max     The maximum length value. Default is 1. e.g. 40
     * @param string $charset The charset to be used when computing value's length with the mb_check_encoding and mb_strlen PHP functions. Default is 'UTF-8'
     *
     *                        Example:
     *                        ```$validator = new Doodle\Validator();
     *                        $violations = $validator->isLength("234");
     *                        echo $violations;
     *
     *                        //  234: This value should have exactly 1 character. (code 4b6f5c76-22b4-409d-af16-fbe823ba9332)
     *                        ```
     *
     * @return object
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function isLength($value, $min = 1, $max = 1, $charset = 'UTF-8')
    {
        return $this->validator->validate($value, [
            new Length(["min" => 1]),
            new NotBlank(),
            new Assert\Length([
                "charset" => $charset,
                "min" => $min,
                "max" => $max,
                "minMessage" => "The value {{ value }} must be at least {{ limit }} characters long",
                "maxMessage" => "The value {{ value }} cannot be longer than {{ limit }} characters",
            ]),
        ]);
    }

    /**
     * Validates if a value is one of a given set of valid choices
     *
     * Checks if its not empty and that is one of a given set of valid choices.
     * It can also be used to validate that each item in an array of items is one of those valid choices.
     *
     * @param mixed $value Value to be validated
     *
     *                     Example:
     *                     ```$validator = new Doodle\Validator();
     *                     $violations = $validator->isEither("c", ["A", "B"]);
     *                     echo $violations;
     *
     *                     //   c: The value "c" should be one of the following "A", "B" (code 8e179f1b-97aa-4560-a02f-2a8b42e49df7)
     *
     * @return object
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function isEither($value, $choices)
    {
        return $this->validator->validate($value, [
            new Length(["min" => 1]),
            new NotBlank(),
            new Assert\Choice([
                "choices" => $choices,
                "message" => "The value {{ value }} should be one of the following {{ choices }}",
            ]),
        ]);
    }
}
