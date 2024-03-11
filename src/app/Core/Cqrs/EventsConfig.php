<?php

namespace App\Core\Cqrs;

use Ds\Map;
use Ds\Set;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class EventsConfig
{
    private Map $events;

    public function __construct()
    {
        $this->events = new Map();
        $this->setHandlers();
    }

    private function setHandlers(): void
    {
        $classes = $this->getAllNameSpaces(app_path());
        foreach ($classes as $item) {
            $classInfo = new ReflectionClass($item);
            if ($classInfo->isAbstract()) {
                continue;
            }
            $events = $classInfo->getAttributes(ListenEvent::class);
            foreach ($events as $event) {
                $this->addHandler($event->newInstance()->getEventName(), $item);
            }
            $interfaces = $classInfo->getInterfaces();
            foreach ($interfaces as $interface) {
                $interfaceInfo = new ReflectionClass($interface);
                $events = $interfaceInfo->getAttributes(ListenEvent::class);
                foreach ($events as $event) {
                    $this->addHandler($event->newInstance()->getEventName(), $item);
                }
            }
        }
    }

    public function getAllNameSpaces($path): array
    {
        $filenames = $this->getFilenames($path);
        $namespaces = [];
        foreach ($filenames as $filename) {
            $namespaces[] = $this->getFullNamespace($filename) . '\\' . $this->getClassName($filename);
        }

        return $namespaces;
    }

    private function getFilenames($path): array
    {
        $finderFiles = Finder::create()->files()->in($path)->name('*.php');
        $filenames = [];
        foreach ($finderFiles as $finderFile) {
            $filenames[] = $finderFile->getRealpath();
        }

        return $filenames;
    }

    private function getFullNamespace($filename): string
    {
        $lines = file($filename);
        $array = preg_grep('/^namespace /', $lines);
        $namespaceLine = array_shift($array);
        $match = [];
        preg_match('/^namespace (.*);$/', $namespaceLine, $match);

        return array_pop($match);
    }

    private function getClassName($filename): string
    {
        $directoriesAndFilename = explode('/', $filename);
        $filename = array_pop($directoriesAndFilename);
        $nameAndExtension = explode('.', $filename);

        return array_shift($nameAndExtension);
    }

    private function addHandler(string $eventName, string $handlerName): void
    {
        if (!$this->events->hasKey($eventName)) {
            $this->events->put($eventName, new Set());
        }
        $this->events->get($eventName)->add($handlerName);
    }

    public static function getInstance(): self
    {
        return Cache::remember('events-config', 3600, static fn() => new self());
    }

    public function getHandlers(string $eventName): Set
    {
        $handlers = $this->events->get($eventName, new Set())->copy();
        $reflector = new ReflectionClass($eventName);
        foreach ($reflector->getInterfaces() as $interface) {
            $interfaceHandlers = $this->events->get($interface->getName(), new Set());
            $handlers->add(...$interfaceHandlers->toArray());
        }

        return $handlers;
    }
}
