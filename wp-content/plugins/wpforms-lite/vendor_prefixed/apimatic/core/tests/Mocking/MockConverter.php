<?php

namespace WPForms\Vendor\Core\Tests\Mocking;

use WPForms\Vendor\Core\Tests\Mocking\Other\MockException;
use WPForms\Vendor\Core\Tests\Mocking\Types\MockApiResponse;
use WPForms\Vendor\Core\Tests\Mocking\Types\MockContext;
use WPForms\Vendor\Core\Tests\Mocking\Types\MockCoreResponse;
use WPForms\Vendor\Core\Tests\Mocking\Types\MockFileWrapper;
use WPForms\Vendor\Core\Tests\Mocking\Types\MockRequest;
use WPForms\Vendor\CoreInterfaces\Core\ContextInterface;
use WPForms\Vendor\CoreInterfaces\Core\Request\RequestInterface;
use WPForms\Vendor\CoreInterfaces\Core\Response\ResponseInterface;
use WPForms\Vendor\CoreInterfaces\Sdk\ConverterInterface;
class MockConverter implements ConverterInterface
{
    public function createApiException(string $message, RequestInterface $request, ?ResponseInterface $response) : MockException
    {
        $response = $response == null ? null : $this->createHttpResponse($response);
        return new MockException($message, $this->createHttpRequest($request), $response);
    }
    public function createHttpContext(ContextInterface $context) : MockContext
    {
        return new MockContext($this->createHttpRequest($context->getRequest()), $this->createHttpResponse($context->getResponse()));
    }
    public function createHttpRequest(RequestInterface $request) : MockRequest
    {
        return new MockRequest($request->getHttpMethod(), $request->getHeaders(), $request->getQueryUrl(), $request->getParameters());
    }
    public function createHttpResponse(ResponseInterface $response) : MockCoreResponse
    {
        return new MockCoreResponse($response->getStatusCode(), $response->getHeaders(), $response->getRawBody());
    }
    public function createApiResponse(ContextInterface $context, $deserializedBody) : MockApiResponse
    {
        $decodedBody = $context->getResponse()->getBody();
        return MockApiResponse::createFromContext($decodedBody, $deserializedBody, $this->createHttpContext($context));
    }
    public function createFileWrapper(string $realFilePath, ?string $mimeType, ?string $filename) : MockFileWrapper
    {
        return MockFileWrapper::createFromPath($realFilePath, $mimeType, $filename);
    }
}
