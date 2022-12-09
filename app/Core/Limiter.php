<?php

class Limiter
{

    private $route;

    public function __construct(string $route)
    {
        $this->route = $route;

        if (!!$this->getSessionCache("last_session_request")) return;

        $this->setSessionCache("request_cnt", 1);
        $this->setSessionCache("last_session_request", time());
    }

    private function setSessionCache(string $key, $value)
    {
        $_SESSION["cache"][$this->route][$key] = $value;
    }

    private function getSessionCache(string $key)
    {
        return $_SESSION["cache"][$this->route][$key] ?? null;
    }

    public function limitRequests(int $max_requests, int $time_interval, callable $handle_time_exceeded)
    {
        $limit_time = time() - $time_interval;

        $request_cnt = $this->getSessionCache("request_cnt");
        $last_session_request = $this->getSessionCache("last_session_request");

        $is_flooded = $last_session_request > $limit_time;

        if (!$is_flooded) {

            $this->setSessionCache("request_cnt", 1);
            $this->setSessionCache("last_session_request", time());

            return;
        }

        if ($request_cnt < $max_requests) {

            $this->setSessionCache("request_cnt", $request_cnt + 1);
            return;
        }

        $time_left = $last_session_request - $limit_time;

        $handle_time_exceeded($time_left);
    }
}
