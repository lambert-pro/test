<?php

$bankResponseExample = [
    BANK_BARCLAYCARD => '4381900031211D18500ACCOUNT
VALID000230322280010020000000012110331163900017602LCB1BWRD381900032303311001MDWNE0EMK 0331',
    BANK_CASHFLOW => '\<?xml version=\'1.0\' encoding=\'utf-8\'?>
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
    BANK_FINARO => '',
    BANK_TEST_BANK => '',
];