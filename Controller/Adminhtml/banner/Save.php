<?php
namespace Raccoon\Banner\Controller\Adminhtml\banner;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action {

    public function __construct(Action\Context $context) {
        parent::__construct($context);
    }

    public function execute() {
		$ds = DIRECTORY_SEPARATOR;
        $data = $this->getRequest()->getPostValue();
        
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_objectManager->create('Raccoon\Banner\Model\Banner');

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
                $model->setCreatedAt(date('Y-m-d H:i:s'));
            }
			
			try {
				$uploader = $this->_objectManager->create(
					'Magento\MediaStorage\Model\File\Uploader',
					['fileId' => 'desktop_media']
				);
				$uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
				
				$imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
				$uploader->setAllowRenameFiles(true);
				$uploader->setFilesDispersion(true);
				
				$mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
													   ->getDirectoryRead(DirectoryList::MEDIA);
				$result = $uploader->save($mediaDirectory->getAbsolutePath('banner_banner'));
				if ($result['error'] == 0) {
					$data['desktop_media'] = 'banner_banner' . $ds . $result['file'];
				}
			} catch (\Exception $e) {
            }
			
			if (isset($data['desktop_media']['delete']) && $data['desktop_media']['delete'] == '1') {
				$data['desktop_media'] = '';
			}

			try {
				$uploader = $this->_objectManager->create(
					'Magento\MediaStorage\Model\File\Uploader',
					['fileId' => 'mobile_media']
				);
				$uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
				
				$imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
				$uploader->setAllowRenameFiles(true);
				$uploader->setFilesDispersion(true);
				
				$mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
													   ->getDirectoryRead(DirectoryList::MEDIA);
				$result = $uploader->save($mediaDirectory->getAbsolutePath('banner_banner'));
				if ($result['error'] == 0) {
					$data['mobile_media'] = 'banner_banner' . $ds . $result['file'];
				}
			} catch (\Exception $e) {
            }
			
            if (isset($data['mobile_media']['delete']) && $data['mobile_media']['delete'] == '1') {
				$data['mobile_media'] = '';
			}
                
            if (isset($data['desktop_media']['value'])) {
				$data['desktop_media'] = $data['desktop_media']['value'];
			}

            if (isset($data['mobile_media']['value'])) {
				$data['mobile_media'] = $data['mobile_media']['value'];
			}

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('The Banner has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Banner.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}