<?php
namespace Linhnv\ChatSystem\Controller\Adminhtml\Chat;

use GuzzleHttp\Exception\GuzzleException;
use Linhnv\ChatSystem\Helper\Data;
use Linhnv\ChatSystem\Model\BlacklistFactory;
use Linhnv\ChatSystem\Model\ChatFactory;
use Linhnv\ChatSystem\Model\ChatMessage;
use Linhnv\ChatSystem\Model\Sender;
use Magecomp\Chatgptaicontent\Model\CompletionConfig;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Linhnv\ChatSystem\Model\ChatGPT\V2 as ChatGPTV2;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManager;

/**
 * Display Hello on screen
 */
class SendmsgChatGpt extends Action
{
    protected $_cacheTypeList;
    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * @var ResponseInterface
     */
    protected $_response;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    protected $_message;

    protected $_chat;

    protected $sender;

    protected $_chatModelFactory;

    protected $httpRequest;

    /**
     * @var RemoteAddress
     */
    protected $remoteAddress;

    protected $blacklistFactory;

    /**
     * @var StoreManager
     */
    protected $storeManager;
    private CompletionConfig $completionConfig;
    public function __construct(
        Context $context,
        StoreManager $storeManager,
        PageFactory $resultPageFactory,
        Data $helper,
        ChatMessage $message,
        Sender $sender,
        ForwardFactory $resultForwardFactory,
        Registry $registry,
        TypeListInterface $cacheTypeList,
        Session $customerSession,
        ChatFactory $chatModelFactory,
        RemoteAddress $remoteAddress,
        Http $httpRequest,
        CompletionConfig $completionConfig,
        BlacklistFactory $blacklistFactory
    ) {
        $this->sender = $sender;
        $this->resultPageFactory    = $resultPageFactory;
        $this->_helper              = $helper;
        $this->_message             = $message;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_cacheTypeList       = $cacheTypeList;
        $this->_request             = $context->getRequest();
        $this->_chatModelFactory    = $chatModelFactory;
        $this->httpRequest          = $httpRequest;
        $this->remoteAddress        = $remoteAddress;
        $this->blacklistFactory     = $blacklistFactory;
        $this->storeManager = $storeManager;
        $this->completionConfig=$completionConfig;
        parent::__construct($context);
    }

    /**
     * Default customer account page
     *
     * @return Page
     * @throws GuzzleException
     */
    public function execute()
    {
        $token = $this->_helper->getConfig('chat_gpt/access_token');
        $data = $this->_request->getPostValue();
        $chatGPT = new ChatGPTV2($token);
        $answers = $chatGPT->ask($data['body_msg']);
//        foreach ($answers as $item){
//            $answer = $item;
//        }
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setHeader('Content-type', 'text/plain');
        $response = $response->setData([
            'answer' => $answers['answer']
        ]);

        return $response;


    }
}
