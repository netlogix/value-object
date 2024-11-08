coding-standards:
	composer rector:fix
	composer composer:normalize:fix
	composer php:lint
	./prettier.sh --write