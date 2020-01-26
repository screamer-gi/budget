<?php

namespace Common\Presentation\Zend\Form\Validator;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsWithoutExclude;

class NoObjectExists extends NoObjectExistsWithoutExclude
{
    /**
     * ObjectRepository from which to search for entities
     *
     * @var EntityRepository ObjectRepository
     */
    protected $objectRepository;

    private $criteria = [];
    private $excludeField = 'id';

    public function setCriteria(array $criteria)
    {
        $this->criteria = $criteria;
    }

    public function setExcludeField($id)
    {
        $this->excludeField = $id;
    }

    public function isValid($value, $context = null)
    {
        $value = $this->cleanSearchValue($value);
        $match = $this->findOneByExcluded($value, $context);

        if ($match) {
            $this->error(self::ERROR_OBJECT_FOUND, $value);
            return false;
        }

        return true;
    }

    private function findOneByExcluded($value, $context)
    {
        $qb = $this->objectRepository->createQueryBuilder('m');
        $x = $qb->expr();
        foreach ($value as $name => $v) {
            $qb->andWhere($qb->expr()->eq("m.$name", $qb->expr()->literal($v)));
        }

        if ($context && !empty($context[$this->excludeField])) {
            $qb->andWhere($qb->expr()->neq('m.id', $context[$this->excludeField]));
        }

        foreach ($this->criteria as $name => $value) {
            $qb->andWhere($x->eq("m.$name", $x->literal($value)));
        }

        try {
            return is_object($qb->getQuery()->getSingleResult());
        } catch (NoResultException $e) {
            return false;
        } catch (NonUniqueResultException $e) {
            return true;
        }
    }
}