# Document Marshaller

[![Build Status](https://travis-ci.org/punkstar/document-marshaller.svg?branch=master)](https://travis-ci.org/punkstar/document-marshaller) [![Coverage Status](https://coveralls.io/repos/github/punkstar/document-marshaller/badge.svg?branch=master)](https://coveralls.io/github/punkstar/document-marshaller?branch=master)

*This is an experiment - use at your own risk!*

The purpose of this library is provide a mechanism for marshalling and unmarshalling data using a single document,
allowing for the transportation and storage of related data in a single file.

Notes on implementation:

* Scalar data types are not preserved

## Document Format

The document is encoded using JSON in the following structure:

    {
        "v": "", // Document Version
        "c": ""  // Document Checksum
        "f": [   // Document Fragments
            {
                "n": "" // Document Fragment Name
                "d": "" // Document Fragment Data
            }
            ...
        ]
    }

### Document

* A document MUST be an object.
* A document SHOULD have a version indicated by the value of the key: `v`.  If a version is absent then a version value
  of `1` MUST be assumed.
* A document SHOULD have a checksum indicated by the value of the key: `c`.  If a checksum is absent then any
  verification logic should be skipped and the user SHOULD be presented with a non fatal warning if appropriate.
* A document SHOULD have a fragments array indicated by the value of the key: `f`.  If the fragments array is absent
  then it MUST be assumed that the document has no fragments.

### Document Version

* The document version MUST be expressed as an integer.

### Document Fragments

* The document fragments MUST be an array of objects with a name key: `n`, and a data key: `d`.
* The name and the data of the document fragment MUST be encoded with base64.

### Document Checksum

* The document checksum MUST be calculated by: sorting the fragments objects by name, then performing a hash of the JSON
  fragments array using sha256.

Some examples are provided below:

The first document consists of two fragments, one called `Header`; the other `Body`.  The content of the `Header`
fragment is the string `Hello`.  The content of the `Body` fragment is `World`.  The JSON representation of these
fragments is as follows:

    [
        {
            "n": "Header",
            "d": "Hello"
        },
        {
            "n": "Body",
            "d": "World"
        }
    ]

The checksum of this document would therefore be:

    sha256([{"n":"SGVhZGVy","d":"SGVsbG8="},{"n":"Qm9keQ==","d":"V29ybGQ="}]) == "d827049039f82a8b65a9b0f52e637cf3bc5d1e0dec7d6198edf901a5e3dcae7d"


The entire document expressed would therefore be:

    {

        "v": 1,
        "c": "d827049039f82a8b65a9b0f52e637cf3bc5d1e0dec7d6198edf901a5e3dcae7d",
        "f": [{
             "n": "Header",
             "d": "Hello"
         }, {
             "n": "Body",
             "d": "World"
        }]
    }

