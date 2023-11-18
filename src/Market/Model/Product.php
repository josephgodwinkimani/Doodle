<?php

declare(strict_types=1);

namespace Market\Model;

use Doctrine\ORM\Exception\ORMException;
use Doodle\DatabaseHelper;
use Doodle\Log;

class Product
{

    /**
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     *
     *
     * @return array
     */
    public function getAll(): array
    {
        try {
            $DB = new DatabaseHelper();
            $entityManager = $DB->init();
            $productRepository = $entityManager->getRepository('Product');

            $products = $productRepository->findAll();

            $appLogger = new Log();

            $appLogger->error(json_encode($products));

            return $products;
        } catch (ORMException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     *
     * @param string $name
     * @param string $createdBy
     *
     * @return object|string
     */
    public function createOne(string $name, string $createdBy): object|string
    {

        try {
            $DB = new DatabaseHelper();
            $entityManager = $DB->init();
            // Declare the class you want to change behavior
            $entityClass = $DB->entityInit("Product");

            $entityClass->setName($name);

            $entityClass->setCreatedBy($createdBy);

            $entityManager->persist($entityClass);

            $entityManager->flush();

            return $entityClass;
        } catch (ORMException $e) {
            return $e->getMessage();
        }
    }
}
