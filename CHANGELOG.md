# Changelog LaravelTaxonomy

## 4.0.0 - 2019-09-26

- Support Laravel 6
- Require "kalnoy/nestedset:^5
- Rename json field `data` => `options`

## 3.2.0 - 2019-05-09

- Support Laravel 5.8

## 3.0.2 - 2018-12-11

- Updated migration
- Updated seed
- Updated docs 

## 3.0.0 - 2018-10-28

- Updated relation term with vocabulary
- Updated DB table fields
- Added in require `lazychaser/laravel-nestedset` (`kalnoy/laravel-nestedset`) to manage tree taxonomy terms 
- Update docs

## 2.1.0 - 2018-07-07

- Remove Softdelete trait in taxonomy models & softdelete field in dbtables
- Update seed & doc

## 2.0.0 - 2018-06-13

- Remove default hierarchy (`HasHierarchy` trait && migration columns form terms)
- Add method `term()` in `HasTaxonomies` trait
- Update documentations

## 1.0.2 - 2017-12-22

- Remove commands

## 1.0.1 - 2017-12-14

- Remove commands

## 1.0.0 - 2017-12-12

- Initial release, make package
