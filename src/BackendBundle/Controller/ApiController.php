<?php

namespace App\BackendBundle\Controller;

use App\BackendBundle\Form\ItemType;
use App\BackendBundle\Service\ItemService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 * @package App\Controller
 * @IsGranted("ROLE_API")
 */
class ApiController extends AbstractController
{
    /**
     * @var ItemService
     */
    private $itemService;

    /**
     * ApiController constructor.
     * @param ItemService $itemService
     */
    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * @Route("/products/{filter}", name="api_products", methods={"GET"}, defaults={"filter":"all"}, requirements={"filter":"more_than_5|all|out_of_stock"})
     */
    public function listAction(string $filter): JsonResponse
    {
        $products =$this->itemService->getProducts($filter);
        return $this->json(array('products'=>$products),Response::HTTP_OK,[],['groups'=>['api']]);
    }

    /**
     * @param Request $request
     * @param int|null $id
     * @return JsonResponse
     * @Route("/product/new", name="api_product_new", methods={"POST"})
     * @Route("/product/{id}/edit", name="api_product_edit", methods={"PUT"}, requirements={"id":"\d+"})
     */
    public function formAction(Request $request,int $id=0): JsonResponse
    {
        $item=$this->itemService->getItem($id);
        if(is_null($item))
        {
            return $this->json(['item'=>'not found'],Response::HTTP_NOT_FOUND);
        }
        $this->checkJsonRequest($request);
        $data=$this->checkData($request);
        $request->request->replace(is_array($data) ? $data : array());
        $form=$this->createForm(ItemType::class,$item);
        $form->submit($request->request->all());
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            return $this->json(array('status' => 'success'), ($id==0? Response::HTTP_CREATED : Response::HTTP_OK));
        }

        return $this->json($this->getGenerateErrorsDataArray($form),400);

    }

    /**
     * @param int $id
     * @Route("/products/{id}/details", name="api_product_details", methods={"GET"}, requirements={"id":"\d+"})
     * @return JsonResponse
     */
    public function itemAction(int $id): JsonResponse
    {
        $item=$this->itemService->getItem($id);
        if(is_null($item))
        {
            return $this->json(['item'=>'not found'],Response::HTTP_NOT_FOUND);
        }
        return $this->json($item,Response::HTTP_OK,[],['groups'=>['api']]);
    }

    /**
     * @param int $id
     * @Route("/products/{id}/delete", name="api_product_delete", methods={"DELETE"}), requirements={"id":"\d+"})
     * @return JsonResponse
     */
    public function deleteAction(int $id): JsonResponse
    {
        $item=$this->itemService->getItem($id);
        if(is_null($item))
        {
            return $this->json(['item'=>'not found'],Response::HTTP_NOT_FOUND);
        }

        $data=$this->itemService->deleteItem($item);
        return $this->json($data,(isset($data['message'])? Response::HTTP_BAD_REQUEST : Response::HTTP_OK));

    }

    private function getGenerateErrorsDataArray(FormInterface $form): array
    {
        return [
            'status'=>'error',
            'type' => 'validation_error',
            'title' => 'There was a validation error',
            'errors' => $this->getErrorsFromForm($form)
        ];
    }

    private function getErrorsFromForm(FormInterface $form): array
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }

    private function checkJsonRequest(Request $request)
    {
        if ($request->getContentType() != 'json' || !$request->getContent()) {
            return $this->json(array('message'=>'Request must be json type'),Response::HTTP_BAD_REQUEST);
        }
    }

    private function checkData(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('invalid json body: ' . json_last_error_msg());
        }
        return $data;
    }


}
