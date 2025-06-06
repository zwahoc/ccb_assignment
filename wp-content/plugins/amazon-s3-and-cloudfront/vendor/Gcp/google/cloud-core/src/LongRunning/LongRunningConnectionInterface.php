<?php

/**
 * Copyright 2016 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace DeliciousBrains\WP_Offload_Media\Gcp\Google\Cloud\Core\LongRunning;

/**
 * Defines the calls required to manage Long Running Operations.
 *
 * This interface should be implemented in a service's Connection namespace.
 * @internal
 */
interface LongRunningConnectionInterface
{
    /**
     * @param array $args
     */
    public function get(array $args);
    /**
     * @param array $args
     */
    public function cancel(array $args);
    /**
     * @param array $args
     */
    public function delete(array $args);
    /**
     * @param array $args
     */
    public function operations(array $args);
}
