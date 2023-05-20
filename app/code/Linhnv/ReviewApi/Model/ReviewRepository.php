<?php

declare(strict_types=1);

namespace Linhnv\ReviewApi\Model;

use Linhnv\ReviewApi\Api\Data\ReviewSearchResultInterface;
use Linhnv\ReviewApi\Model\Review\Command\DeleteByIdInterface;
use Linhnv\ReviewApi\Model\Review\Command\GetInterface;
use Linhnv\ReviewApi\Model\Review\Command\GetListInterface;
use Linhnv\ReviewApi\Model\Review\Command\SaveInterface;
use Linhnv\ReviewApi\Api\ReviewRepositoryInterface;
use Linhnv\ReviewApi\Api\Data\ReviewInterface;
use Magento\Framework\Api\SearchCriteria;

/**
 * @inheritdoc
 */
class ReviewRepository implements ReviewRepositoryInterface
{
    /**
     * @var SaveInterface
     */
    private $commandSave;

    /**
     * @var GetInterface
     */
    private $commandGet;

    /**
     * @var GetListInterface
     */
    private $commandGetList;

    /**
     * @var DeleteByIdInterface
     */
    private $commandDeleteById;

    /**
     * ReviewRepository constructor.
     *
     * @param GetInterface $commandGet
     * @param SaveInterface $commandSave
     * @param GetListInterface $commandGetList
     * @param DeleteByIdInterface $commandDeleteById
     */
    public function __construct(
        GetInterface $commandGet,
        SaveInterface $commandSave,
        GetListInterface $commandGetList,
        DeleteByIdInterface $commandDeleteById
    ) {
        $this->commandGet = $commandGet;
        $this->commandSave = $commandSave;
        $this->commandGetList = $commandGetList;
        $this->commandDeleteById = $commandDeleteById;
    }

    /**
     * @inheritdoc
     *
     * @param ReviewInterface $review
     *
     * @return ReviewInterface
     * @throws \Linhnv\ReviewApi\Validation\ValidationException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function save(ReviewInterface $review): ReviewInterface
    {
        return $this->commandSave->execute($review);
    }

    /**
     * @inheritdoc
     *
     * @param int $reviewId
     *
     * @return ReviewInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $reviewId): ReviewInterface
    {
        return $this->commandGet->execute($reviewId);
    }

    /**
     * @inheritdoc
     *
     * @param SearchCriteria|null $searchCriteria
     *
     * @return ReviewSearchResultInterface
     */
    public function getList(SearchCriteria $searchCriteria = null): ReviewSearchResultInterface
    {
        return $this->commandGetList->execute($searchCriteria);
    }

    /**
     * @inheritdoc
     *
     * @param int $reviewId
     *
     * @return void
     *
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $reviewId): void
    {
        $this->commandDeleteById->execute($reviewId);
    }
}
