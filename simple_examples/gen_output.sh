#!/bin/bash
echo
for f in *.php; do
	echo "----------" $f "------------------------------------------"
	php $f
done
echo "----------------------------------------------------"

