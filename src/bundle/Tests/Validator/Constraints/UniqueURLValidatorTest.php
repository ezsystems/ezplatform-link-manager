<?php

namespace EzSystems\EzPlatformLinkManagerBundle\Test\Validator\Constraints;

use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use EzSystems\EzPlatformLinkManager\API\Repository\URLService;
use EzSystems\EzPlatformLinkManager\API\Repository\Values\URL;
use EzSystems\EzPlatformLinkManagerBundle\Form\Data\URLUpdateData;
use EzSystems\EzPlatformLinkManagerBundle\Validator\Constraints\UniqueURL;
use EzSystems\EzPlatformLinkManagerBundle\Validator\Constraints\UniqueURLValidator;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class UniqueURLValidatorTest extends TestCase
{
    /** @var \EzSystems\EzPlatformLinkManager\API\Repository\URLService|\PHPUnit_Framework_MockObject_MockObject */
    private $urlService;

    /** @var \Symfony\Component\Validator\Context\ExecutionContextInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $executionContext;

    /** @var \EzSystems\EzPlatformLinkManagerBundle\Validator\Constraints\UniqueURLValidator */
    private $validator;

    protected function setUp()
    {
        $this->urlService = $this->createMock(URLService::class);
        $this->executionContext = $this->createMock(ExecutionContextInterface::class);

        $this->validator = new UniqueURLValidator($this->urlService);
        $this->validator->initialize($this->executionContext);
    }

    public function testUnsupportedValueType()
    {
        $value = new stdClass();

        $this->urlService
            ->expects($this->never())
            ->method('loadByUrl');

        $this->executionContext
            ->expects($this->never())
            ->method('buildViolation');

        $this->validator->validate($value, new UniqueURL());
    }

    public function testValid()
    {
        $url = 'http://ez.no';

        $this->urlService
            ->expects($this->once())
            ->method('loadByUrl')
            ->with($url)
            ->willThrowException($this->createMock(NotFoundException::class));

        $this->executionContext
            ->expects($this->never())
            ->method('buildViolation');

        $this->validator->validate(new URLUpdateData([
            'id' => 1,
            'url' => $url,
        ]), new UniqueURL());
    }

    public function testInvalid()
    {
        $constraint = new UniqueURL();
        $url = 'http://ez.no';

        $this->urlService
            ->expects($this->once())
            ->method('loadByUrl')
            ->with($url)
            ->willReturn(new URL([
                'id' => 2,
                'url' => $url,
            ]));

        $constraintViolationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);

        $this->executionContext
            ->expects($this->once())
            ->method('buildViolation')
            ->with($constraint->message)
            ->willReturn($constraintViolationBuilder);

        $constraintViolationBuilder
            ->expects($this->once())
            ->method('atPath')
            ->with('url')
            ->willReturn($constraintViolationBuilder);

        $constraintViolationBuilder
            ->expects($this->once())
            ->method('setParameter')
            ->with('%url%', $url)
            ->willReturn($constraintViolationBuilder);

        $constraintViolationBuilder
            ->expects($this->once())
            ->method('addViolation');

        $this->validator->validate(new URLUpdateData([
            'id' => 1,
            'url' => $url,
        ]), $constraint);
    }

    public function testEditingIsValid()
    {
        $id = 1;
        $url = 'http://ez.no';

        $this->urlService
            ->expects($this->once())
            ->method('loadByUrl')
            ->with($url)
            ->willReturn(new URL([
                'id' => $id,
                'url' => $url,
            ]));

        $this->executionContext
            ->expects($this->never())
            ->method('buildViolation');

        $this->validator->validate(new URLUpdateData([
            'id' => $id,
            'url' => $url,
        ]), new UniqueURL());
    }
}
