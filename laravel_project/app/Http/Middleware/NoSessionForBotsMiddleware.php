<?php

namespace App\Http\Middleware;

use Closure;
use Crawler;

class NoSessionForBotsMiddleware
{
    // Blocked IP addresses
    public $restrictedIp = [
    "185.191.171.34","185.191.171.23","114.119.151.170","114.119.140.175","114.119.133.87","114.119.159.216","114.119.139.94","114.119.129.34","114.119.144.127","114.119.158.251","114.119.157.82","114.119.129.158","114.119.155.2","114.119.138.173","164.132.201.7","158.69.123.143","114.119.150.203","167.114.103.160","114.119.132.131","114.119.136.144","114.119.146.159","114.119.141.34","114.119.136.64","114.119.135.78","114.119.155.103","114.119.145.134","114.119.141.152","114.119.134.72","114.119.149.71","114.119.148.187","114.119.137.124","114.119.144.30","114.119.131.11","114.119.148.33","114.119.148.237","114.119.153.49","114.119.142.140","114.119.134.25","114.119.135.206","114.119.158.253","114.119.136.87","114.119.143.51","114.119.141.2","114.119.142.97","114.119.156.31","114.119.148.154","114.119.137.238","114.119.130.27","114.119.133.69","114.119.147.184","114.119.132.42","114.119.142.232","114.119.161.177","114.119.135.199","114.119.156.99","114.119.157.68","114.119.134.245","114.119.131.35","114.119.152.216","114.119.132.18","114.119.140.44","114.119.155.118","114.119.157.157","114.119.145.81","114.119.149.173","114.119.154.207","114.119.147.233","114.119.132.112","114.119.159.122","114.119.156.120","114.119.157.56","114.119.148.191","114.119.151.83","114.119.142.131","185.234.69.198","65.108.40.25","114.119.151.153","114.119.137.67","173.212.220.26","114.119.145.129","114.119.131.199","185.191.171.9","114.119.140.172","114.119.129.22","185.191.171.38","158.69.22.90","185.191.171.21","114.119.142.121","114.119.133.166","185.191.171.3","114.119.142.100","114.119.137.82","114.119.143.119","114.119.140.238","114.119.156.114","114.119.156.193","114.119.157.221","114.119.141.30","114.119.137.174","114.119.144.31","185.191.171.14","114.119.128.56","114.119.136.249","114.119.137.28","114.119.153.106","114.119.129.205","38.242.151.181","114.119.147.75","185.191.171.25","185.191.171.13","167.114.64.97","114.119.133.46","114.119.158.6","114.119.156.235","114.119.154.73","114.119.132.36","114.119.156.154","114.119.158.97","114.119.150.155","114.119.131.192","114.119.129.208","114.119.143.104","114.119.158.133","114.119.156.55","114.119.137.101","114.119.154.128","114.119.157.237","114.119.151.117","114.119.132.146","114.119.152.86","114.119.129.82","114.119.140.102","114.119.140.71","114.119.129.74","114.119.151.17","114.119.134.161","149.202.87.168","114.119.133.83","114.119.159.17","114.119.153.38","114.119.157.210","114.119.151.4","65.108.2.171","195.201.199.99","114.119.133.158","114.119.139.35","114.119.153.105","114.119.132.65","114.119.142.73","114.119.128.26","114.119.152.5","167.114.211.237","114.119.136.99","114.119.130.201","114.119.139.246","114.119.158.142","114.119.158.36","114.119.137.237","114.119.152.128","114.119.144.132","114.119.146.41","114.119.131.211","114.119.154.13","114.119.138.107","114.119.136.34","114.119.139.110","217.76.60.60","114.119.134.146","114.119.158.203","114.119.143.168","114.119.151.0","114.119.157.51","114.119.134.195","114.119.129.33","114.119.153.118","114.119.130.87","114.119.143.18","114.119.151.64","114.119.140.74","114.119.141.18","114.119.152.221","114.119.157.239","114.119.145.126","161.97.145.53","135.181.213.219","114.119.131.195","114.119.150.208","164.132.201.165","114.119.135.139","114.119.156.185","114.119.150.28","114.119.132.7","114.119.138.36","114.119.162.62","114.119.132.61","114.119.133.134","114.119.146.145","114.119.138.205","114.119.148.163","114.119.152.167","114.119.155.121","114.119.148.56","114.119.133.42","114.119.131.206","114.119.137.193","114.119.137.252","114.119.150.29","114.119.155.126","114.119.139.8","167.114.158.215","114.119.147.211","114.119.151.197","114.119.149.1","114.119.150.158","114.119.156.138","114.119.139.205","114.119.131.254","114.119.144.37","114.119.145.194","114.119.141.186","114.119.156.132","114.119.154.22","114.119.133.232","114.119.150.15","114.119.156.77","114.119.136.246","114.119.146.126","114.119.130.31","114.119.150.95","114.119.141.9","114.119.146.16","114.119.133.30","114.119.137.91","114.119.134.192","114.119.154.237","114.119.150.85","114.119.139.44","114.119.129.167","114.119.140.131","114.119.143.226","114.119.129.191","114.119.151.131","114.119.149.199","114.119.133.146","114.119.145.140","114.119.146.171","114.119.144.7","114.119.131.139","114.119.143.185","114.119.140.122","114.119.152.207","114.119.150.56","114.119.148.252","114.119.128.5","114.119.131.245","114.119.146.195","114.119.147.213","114.119.143.215","114.119.152.108","114.119.134.224","114.119.130.109","185.191.171.45","114.119.156.115","114.119.131.120","114.119.157.245","114.119.150.16","207.180.220.114","144.91.125.96","114.119.138.95","114.119.141.178","114.119.133.223","114.119.138.235","114.119.150.110","114.119.132.57","185.191.171.2","38.242.155.167","114.119.152.148","114.119.133.63","114.119.141.67","114.119.156.251","65.108.46.72","114.119.159.87","114.119.128.57","114.119.133.152","114.119.144.117","114.119.159.64","114.119.151.156","114.119.150.196","114.119.141.135","114.119.148.10","185.191.171.15","114.119.128.35","114.119.132.70","65.108.100.146","37.187.73.121","185.191.171.41","114.119.167.18","114.119.151.119","114.119.153.16","114.119.144.65","114.119.155.13","114.119.140.111","114.119.142.135","114.119.135.159","114.119.152.34","114.119.135.36","114.119.143.177","114.119.131.28","114.119.146.70","114.119.143.209","114.119.146.212","114.119.148.242","114.119.158.34","114.119.144.212","114.119.156.209","167.114.173.115","114.119.152.246","185.191.171.42","114.119.155.235","114.119.157.43","38.242.214.79","192.99.37.116","149.202.87.182","114.119.135.30","185.191.171.20","114.119.154.103","114.119.156.72","114.119.155.83","114.119.151.126","114.119.136.13","185.191.171.4","114.119.130.18","185.191.171.11","114.119.137.95","114.119.137.204","114.119.159.75","114.119.141.103","114.119.133.208","114.119.151.219","185.191.171.8","114.119.133.142","114.119.157.39","114.119.152.64","114.119.147.32","114.119.145.236","114.119.146.4","114.119.135.73","114.119.153.122","114.119.145.201","114.119.146.252","84.46.255.141","167.114.116.25","185.191.171.36","164.132.201.56","173.212.246.91","114.119.137.211","114.119.137.142","114.119.153.169","114.119.142.186","114.119.132.149","114.119.144.23","114.119.144.202","114.119.148.13","114.119.143.124","114.119.145.163","114.119.155.14","114.119.143.247","114.119.131.136","148.251.4.36","114.119.140.38","114.119.156.56","114.119.145.56","185.191.171.24","192.99.15.199","178.151.245.174","94.23.203.86","5.253.19.58","185.191.171.33","181.92.157.49","185.191.171.1","114.119.148.105","114.119.145.5","62.138.2.243","114.119.146.219","114.119.157.236","114.119.159.228","114.119.129.171","114.119.155.92","114.119.155.224","114.119.159.33","164.132.201.57","71.13.87.122","114.119.133.78","114.119.149.51","185.191.171.10","114.119.153.137","114.119.153.121","185.191.171.37","185.191.171.6","185.191.171.35","114.119.150.75","114.119.151.84","114.119.142.126","192.151.157.210","114.119.129.2","185.191.171.44","114.119.149.116","84.46.255.144","158.69.22.93","114.119.158.58","66.249.79.153","185.191.171.40","218.188.41.122","167.114.116.38","114.119.153.20","114.119.142.99","158.69.22.96","114.119.144.11","164.132.201.78","114.119.129.4","114.119.150.227","114.119.137.139","192.99.14.19","149.202.87.160","192.99.37.138","114.119.134.217","114.119.137.43","66.249.68.58","135.181.74.243","66.249.68.62","95.91.85.50","65.108.110.26","114.119.135.121","114.119.129.233","37.187.94.3","95.217.109.26","193.70.80.150","135.181.212.177","149.202.87.176","114.119.156.238","161.97.91.204","92.220.10.100","202.61.253.63","94.23.203.202","192.95.30.21","135.181.213.220","65.108.64.210","91.219.254.103","164.132.201.179","135.181.79.106","5.9.158.195","51.222.253.1","65.108.124.153","195.191.219.131","51.222.253.11","51.222.253.10","51.222.253.9","51.222.253.19","51.222.253.15","65.108.128.54","51.222.253.17","51.222.253.7","51.222.253.14","51.222.253.16","51.222.253.5","65.108.0.71","114.119.154.87","66.249.79.126","149.202.87.178","66.249.79.98","51.222.253.8","51.222.253.6","51.222.253.4","51.222.253.13","51.222.253.3","149.202.82.11","51.222.253.18","51.222.253.20","51.222.253.2","51.222.253.12","66.249.79.96","65.21.232.254","95.217.195.123","192.99.15.33","149.202.87.137","114.119.149.7","65.108.78.33","167.114.101.143","149.202.87.37","95.91.75.79","114.119.146.77","66.249.79.230","66.249.79.234","66.249.79.232","185.191.171.17","185.191.171.7","114.119.145.130","185.191.171.18","216.244.66.194","66.249.68.60","114.119.149.145","114.119.154.67","66.249.79.151","66.249.79.155","114.119.146.229","114.119.157.158","114.119.139.220","114.119.145.86","114.119.136.148","114.119.140.250","114.119.130.14","5.189.141.124","192.95.29.186","135.181.75.58","167.114.101.65","207.180.226.173","192.99.15.17","114.119.138.111","95.111.247.252","192.99.101.79","37.187.73.123","164.132.203.193","192.95.29.138","84.46.255.139","114.119.154.63","114.119.132.43","185.216.203.239","114.119.132.171","114.119.130.175","164.132.201.51","114.119.132.105","37.187.89.104","185.234.69.215","130.185.119.78","188.165.232.135","114.119.140.29","114.119.129.71","167.114.157.181","114.119.129.143","149.202.87.139","114.119.152.245","114.119.146.255","114.119.139.66","158.69.23.160","149.202.87.164","114.119.132.187","114.119.151.174","207.46.13.217","52.167.144.69","114.119.143.172","149.154.161.246","114.119.143.225","185.191.171.12","192.99.15.34","114.119.157.225","114.119.147.6","114.119.137.8",
    "114.119.155.233","114.119.145.65","114.119.146.29","114.119.135.72","114.119.152.147","114.119.141.42","114.119.155.154","114.119.153.104","114.119.134.196","114.119.144.209","114.119.151.184","114.119.137.105","114.119.156.19","114.119.144.105","114.119.130.246","114.119.143.77","114.119.151.89","114.119.166.63","114.119.151.208","114.119.159.92","114.119.132.25","114.119.153.212","114.119.158.45","114.119.157.250","114.119.130.26","114.119.130.255","114.119.152.69","114.119.163.52","114.119.141.71","114.119.141.79","114.119.132.101","114.119.144.80","114.119.156.181","114.119.147.129","114.119.136.31","114.119.138.99","114.119.144.63","114.119.149.137","114.119.149.195","114.119.129.200","114.119.132.207","114.119.130.116","114.119.133.138","114.119.129.244","114.119.144.233","114.119.162.80","114.119.140.166","114.119.137.195","114.119.144.42","114.119.157.26","114.119.144.55","114.119.146.107","114.119.150.37","114.119.150.6","114.119.148.1","114.119.142.243","114.119.149.66","114.119.150.94","114.119.131.90","114.119.131.10","158.69.22.94","114.119.151.209","114.119.134.75","114.119.143.152","114.119.159.161","114.119.142.14","114.119.158.167","114.119.162.251","114.119.133.125","114.119.142.197","114.119.140.21","135.181.180.59","114.119.137.146","185.191.171.5","114.119.130.32","114.119.135.245","114.119.132.23","185.191.171.22","185.191.171.39","185.191.171.16","149.202.86.231"
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Crawler::isCrawler()) {
            return response()->json(['message' => "You are not allowed to access this site."]);
        }
        if(Crawler::isCrawler($_SERVER['HTTP_USER_AGENT'])) {
            return response()->json(['message' => "You are not allowed to access this site."]);
        }
        if (in_array($request->ip(), $this->restrictedIp)) {
            return response()->json(['message' => "You are not allowed to access this site."]);
        }

        if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])){
            return response()->json(['message' => "You are not allowed to access this site."]);
        }
        return $next($request);
    }     
}