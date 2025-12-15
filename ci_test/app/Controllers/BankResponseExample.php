<?php

$bankResponseExampleByVisa = [
    // https://hub.acquired.com/#transactions/detail/142878299
    BANK_BARCLAYCARD => '4381900590757D18500ACCOUNT VALID000230522280010020000000007570509184600944571LCB1BWRD381900592305091001483129639851483',
    // 136795209
    BANK_CASHFLOW => 'A|03P067BE7F8|222|234720|Authorised|74501873090042182079063|85|483090825711807',
    // 136794899
    BANK_FINARO => 'K=ae2c52345f17e9287d47c135199c73864f48a331564216ded07e5cd4655aefc5&M=10032215&O=2&T=03%2F31%2F2023+22%3A41%3A24&a1=136794899&a2=2&a4=0&a5=GBP&a9=1&b1=446291...5717&b2=1&b20=V0010013823090816840104021615&b3=04&b4=27&z1=XZZ5574224ebd11f6D4H47SCL55IBAP7&z13=309015747326&z14=M&z2=0&z3=Transaction+has+been+executed+successfully.&z33=CREDORAX&z34=R0065458&z39=XZZ5574224ebd11f6D4H47SCL55IBAP7&z4=577156&z41=85&z50=483090816840090&z55=XZZ5574224ebd11f6D4S73YPJGLV5ASF&z6=85',
    // 136795227
    BANK_TEST_BANK => '<?xml version=\'1.0\' encoding=\'utf-8\'?>
<responseblock  version="3.67">
    <requestreference>W56-HFExGc3F</requestreference>
    <response  type="ACCOUNTCHECK">
        <acquirerresponsecode>83</acquirerresponsecode>
        <acquirerresponsemessage>Not declined - Valid for all zero amount transactions and Visa refunds</acquirerresponsemessage>
        <authcode>005331</authcode>
        <billing>
            <amount  currencycode="GBP">0</amount>
            <dcc  enabled="0"/>
            <payment  type="DELTA">
                <issuer>LLOYDS BANK PLC</issuer>
                <issuercountry>GB</issuercountry>
                <pan>492181######8464</pan>
            </payment>
            <subscription  type="RECURRING">
                <number>1</number>
            </subscription>
        </billing>
        <customer>
            <accountnumber  type="CARD">492181######8464</accountnumber>
            <dob>1995-05-23</dob>
        </customer>
        <error>
            <code>0</code>
            <message>Ok</message>
        </error>
        <live>1</live>
        <merchant>
            <chargedescription>Abound by Fintern</chargedescription>
            <debtrepayment>1</debtrepayment>
            <merchantcategorycode>6012</merchantcategorycode>
            <merchantcity>Peterborough</merchantcity>
            <merchantcountryiso2a>GB</merchantcountryiso2a>
            <merchantname>FINTERN LTD</merchantname>
            <merchantnumber>000104960049601</merchantnumber>
            <merchantzipcode>EC2A 4NE</merchantzipcode>
            <operatorname>rmurphy@acquired.com</operatorname>
            <orderreference>136795227</orderreference>
        </merchant>
        <operation>
            <accounttypedescription>ECOM</accounttypedescription>
            <credentialsonfile>1</credentialsonfile>
            <cryptocurrencyindicator>0</cryptocurrencyindicator>
            <parenttransactionreference>56-102-8121035</parenttransactionreference>
            <schemereferencedata>483090826294296</schemereferencedata>
        </operation>
        <other>
            <retrievalreferencenumber>309022560503</retrievalreferencenumber>
            <stan>560503</stan>
        </other>
        <security>
            <address>2</address>
            <postcode>2</postcode>
            <securitycode>2</securitycode>
        </security>
        <settlement>
            <settleduedate>2023-03-31</settleduedate>
            <settlestatus>2</settlestatus>
        </settlement>
        <threedsecure>
            <cavv>AAkBCZMSkwAAAAAAgmCQdAAAAAA=</cavv>
            <eci>05</eci>
            <enrolled>Y</enrolled>
            <status>Y</status>
            <version>2.1.0</version>
        </threedsecure>
        <timestamp>2023-03-31 22:57:08</timestamp>
        <transactionreference>56-70-55620503</transactionreference>
    </response>
    <secrand>cpzCA3mQeI</secrand>
</responseblock>',
];

$bankResponseExampleByMastercard = [
    // 136770331
    BANK_BARCLAYCARD => '4381900031211D18500ACCOUNT VALID000230322280010020000000012110331163900017602LCB1BWRD381900032303311001MDWNE0EMK      0331',
    // 136795281
    BANK_CASHFLOW => 'A|03P067BE8D7|242|AM2S1M|Authorised|75269373090040781623273|00|MDHPQXSH10331',
    // 136794649
    BANK_FINARO => 'K=67611aa1d7a12ec14fd6cfca6b24734f37ce9a0b7cb5cb2b12d2c9178aa52374&M=10032215&O=2&T=03%2F31%2F2023+22%3A30%3A01&a1=136794649&a2=2&a4=0&a5=GBP&a9=1&b1=51676799...7294&b2=2&b3=04&b4=26&z1=XZZ5876ab57b607c541EDUPMXXM5RACL&z13=309018640836&z14=M&z2=0&z3=Transaction+has+been+executed+successfully.&z33=CREDORAX&z34=R0065458&z39=XZZ5876ab57b607c541EDUPMXXM5RACL&z4=564808&z41=85&z50=MDWWR29FL0331&z55=XZZ5876ab57b607c541COXEE7JNKJCXZ&z6=85',
    // 136795203
    BANK_TEST_BANK => '<?xml version=\'1.0\' encoding=\'utf-8\'?>
<responseblock  version="3.67">
    <requestreference>W55-dtRH6nem</requestreference>
    <response  type="ACCOUNTCHECK">
        <acquirerresponsecode>00</acquirerresponsecode>
        <acquirerresponsemessage>Approved or completed Successfully</acquirerresponsemessage>
        <authcode>ACFFBV</authcode>
        <billing>
            <amount  currencycode="GBP">0</amount>
            <dcc  enabled="0"/>
            <payment  type="MASTERCARDDEBIT">
                <issuer>MONZO BANK LIMITED</issuer>
                <issuercountry>GB</issuercountry>
                <pan>516859######3354</pan>
            </payment>
            <subscription  type="RECURRING">
                <number>1</number>
            </subscription>
        </billing>
        <customer>
            <accountnumber  type="CARD">516859######3354</accountnumber>
            <dob>1996-08-01</dob>
        </customer>
        <error>
            <code>0</code>
            <message>Ok</message>
        </error>
        <live>1</live>
        <merchant>
            <chargedescription>LOQBOX Save</chargedescription>
            <debtrepayment>0</debtrepayment>
            <merchantcategorycode>6012</merchantcategorycode>
            <merchantcity>Bristol</merchantcity>
            <merchantcountryiso2a>GB</merchantcountryiso2a>
            <merchantname>DDC FINANCIAL SOLUTIONS</merchantname>
            <merchantnumber>000104960046437</merchantnumber>
            <merchantzipcode>BS9 4PN</merchantzipcode>
            <operatorname>rmurphy@acquired.com</operatorname>
            <orderreference>136795203</orderreference>
        </merchant>
        <operation>
            <accounttypedescription>ECOM</accounttypedescription>
            <credentialsonfile>1</credentialsonfile>
            <cryptocurrencyindicator>0</cryptocurrencyindicator>
            <parenttransactionreference>55-102-8170466</parenttransactionreference>
            <schemereferencedata>MDHCQE4S1   0331</schemereferencedata>
        </operation>
        <other>
            <retrievalreferencenumber>309022551524</retrievalreferencenumber>
            <stan>551524</stan>
        </other>
        <security>
            <address>4</address>
            <postcode>2</postcode>
            <securitycode>2</securitycode>
        </security>
        <settlement>
            <settleduedate>2023-03-31</settleduedate>
            <settlestatus>2</settlestatus>
        </settlement>
        <threedsecure>
            <cavv>kBON7YVw/+j34gAAS0KAdYJhiABc</cavv>
            <eci>02</eci>
            <enrolled>Y</enrolled>
            <status>Y</status>
            <version>2.2.0</version>
        </threedsecure>
        <timestamp>2023-03-31 22:55:34</timestamp>
        <transactionreference>55-70-55291524</transactionreference>
    </response>
    <secrand>eZ</secrand>
</responseblock>',
];