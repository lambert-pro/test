<?php

$pem = "-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEA9/3EGB8W3ar1ZbB0DCtf6h5hD0C5bPv7p/ZMNYzsMEViQxs6
gkixlmdnUJIUR5AMaXFoFlihkMkhUOcevlkweUhv1V5RzHLQg6UVFWg0vKeyZaZ/
lbcQPDnKIueyTTToRtpOKORyRhPlSaN5UYAtFN3CoLzTK2Cxa290kOX3UJ0R/4ZU
2+Zm+amMRtMIFtWc00S5px81Ctn5AY1XEzY4rPUftdJTfZHBt5FdBt/ZoMnUTcIx
aul4kte2jarVVv0BdXbaW8ImYdVTJUmdA8M1PlQ8p3T71wRqXbdEootQp9sWV9pH
TRkF0ypShn16MN/5uaqEHmoAzz7yafjKeqej/wIDAQABAoIBAQC8iJCsRfZ8T5yA
0sVm+xLQSog/sFVIJcoMx5Loo1ps2FL78ZdptRpN3g8NkgEY5sqI307irj8mc8KA
XzVgQS45Bnj/HdXSOPeNHdQJkk+FnXhjD1Gv4JzXLJggMUW8rJxqQU1qiULXRAjt
EvsImwmq820kBmoEcF5x7yoPfsWm4kPPLVk3ZjH3+EW2w5Dn35T59E7+AFLqxZ41
JmP3fTgBPY8yzzM8zXYfzYDOqfLY0uhsyw4LGWSxNG0Huji/EknhfZ4i3TnXGW9z
9QBv0us61N9S77vrr/2F40ScMX5gcw+Gayv2nGhV4nDj62NYwZBiv8Ph9PCtPnvV
WRKsov0BAoGBAPzLLUnqwj/n5qGB17i2amZheqzm4z+3NJX88rVUf1RaKW9Enwwn
fTeGd3orJbWB1Uyj8dms9os+OWVszXiLNJVa4sL26VkY/smo11NgbR5+1+soq7Os
hPnA3jqLVUfJ5VbwLb7rlhIi+7R8xxLxN58xzI+G+MfuaGfUejvJgBFzAoGBAPsi
/uXsYEgggaeb/mUFaRdnDeimLWcMKYiIR6K+DPe7lYLQaDC/zENCWLDlCzEYg2dG
PYsXWsIyyJTjf85HiJTxRVYzDUS2aAOUl9WysTPHiqceB9lfDPUFlBgRq/NmgJl1
LvydpVIij+seHpuDNWDBqNBpHPGseZkdhpKb1VBFAoGAWbVccAPARV9tR9lFDYam
gYiMOTmCYYUJQ0TNeK3wtaV9WMAYVP7af87XLWKMcjoN0LHJTL8FiupdAfI3hFSa
J3pmSFvI+VZWbIffSfZJIu5Of2QicpOBaQQZmNsDO4OZQF3hTgRacDs76ZPyLXWu
kG7isfhq5sBjCp2rdvYN3aMCgYEAyrg3FhY2qkJDJq8PLTCu4ks3uQLbR4FTzXhk
iwPqp9buG0hrsl5AXlKiETjyTdFB0Q2sBCj4BCbGLxltQ3AO2lvf4nMXVM4BLFK4
NbImxGtgiwH8yASoCulT4BHzwWiOilFDensuxhxMHDiV8GZ7ofzxbjpLOPJGvchN
pu7PxBkCgYEAsx2gVd3ozE7VglPxczKToKEW5EJ7we5wOpocF2UseiVV1N4wiq52
Wh482baHogK5m5o5YsLA5J1BUx9/xh/dpYraCMWhGho9kWTJNzpx6TrC6Wx9tZXf
YEHerzsRYjPNMu4T64yholBaqBe4e+QyEYKWDDiXW/ZPxzk3zGFvW5E=
-----END RSA PRIVATE KEY-----";

$privateKey = openssl_pkey_get_private($pem, '');
print_r($privateKey);die;