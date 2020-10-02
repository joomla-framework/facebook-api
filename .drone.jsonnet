local volumes = [
    {
        name: "composer-cache",
        path: "/tmp/composer-cache",
    },
];

local hostvolumes = [
    {
        name: "composer-cache",
        host: {path: "/tmp/composer-cache"}
    },
];

local composer(phpversion, params) = {
    name: "composer",
    image: "joomlaprojects/docker-images:php" + phpversion,
    volumes: volumes,
    commands: [
        "php -v",
        "composer update " + params
    ]
};

local phpunit(phpversion) = {
    name: "PHPUnit",
    image: "joomlaprojects/docker-images:php" + phpversion,
    [if phpversion == "8.0" then "failure"]: "ignore",
    commands: ["vendor/bin/phpunit"]
};

local pipeline(name, phpversion, params) = {
    kind: "pipeline",
    name: "PHP " + name,
    volumes: hostvolumes,
    steps: [
        composer(phpversion, params),
        phpunit(phpversion)
    ],
};

[
    {
        kind: "pipeline",
        name: "Codequality",
        volumes: hostvolumes,
        steps: [
            {
                name: "composer",
                image: "joomlaprojects/docker-images:php7.1",
                volumes: volumes,
                commands: [
                    "php -v",
                    "composer update",
                    "composer require phpmd/phpmd phpstan/phpstan"
                ]
            },
            {
                name: "phpcs",
                image: "joomlaprojects/docker-images:php7.1",
                depends: [ "composer" ],
                commands: [
                    "vendor/bin/phpcs --config-set installed_paths vendor/joomla/coding-standards",
                    "vendor/bin/phpcs -p --report=full --extensions=php --standard=Joomla src/"
                ]
            }
        ]
    },
    {
        kind: "pipeline",
        name: "PHP 5.3 lowest",
        volumes: hostvolumes,
        steps: [
            {
                name: "composer",
                image: "joomlaprojects/docker-images:php5.3",
                volumes: volumes,
                commands: [
                    "php -v",
                    "composer update --prefer-stable --prefer-lowest",
                    "composer update phpunit/phpunit-mock-objects"
                ]
            },
            phpunit("5.3")
        ]
    },
    pipeline("5.3", "5.3", "--prefer-stable"),
    pipeline("5.4", "5.4", "--prefer-stable"),
    pipeline("5.5", "5.5", "--prefer-stable"),
    pipeline("5.6", "5.6", "--prefer-stable"),
    pipeline("7.0", "7.0", "--prefer-stable"),
    pipeline("7.1", "7.1", "--prefer-stable")
]
