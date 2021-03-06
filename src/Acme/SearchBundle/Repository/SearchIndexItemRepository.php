<?php

namespace Acme\SearchBundle\Repository;

use Acme\SearchBundle\Entity\SearchIndexItem;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping;


/**
 * SearchIndexItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SearchIndexItemRepository extends EntityRepository
{

    /**
     * Construct
     */

    public function __construct(EntityManager $em, Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
    }


    /**
     * Build searchEntityItem from arguments
     * @param string $content
     * @param int $entityId
     * @param string $entityType
     * @return SearchIndexItem
     */
    public function buildSearchIndexItem(string $content, int $entityId, string $entityType)
    {
        $e = new SearchIndexItem();
        $e->setContent($content);
        $e->setEntityId($entityId);
        $e->setEntityType($entityType);
        return $e;
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->_em;
    }

    /**
     * @param array $predicatesAssoc
     * @return SearchIndexItem[]
     */
    public function findItemsDynamically(array $predicatesAssoc)
    {
        $queryBuilder = $this->createQueryBuilder('sit');
        $expBuilder = $queryBuilder->expr();

        foreach($predicatesAssoc as $argName => $argValue)
        {
            if (!empty($argValue))
            {
                $queryBuilder->andWhere($expBuilder->eq('sit.' . $argName, ':' . $argName));
                $queryBuilder->setParameter(':' . $argName, (int) $argValue);
            }
        }

        $query = $queryBuilder->getQuery();
        $items = $query->getResult();
        return $items;
    }
}
