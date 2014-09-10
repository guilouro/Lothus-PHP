#!/usr/bin/env python
# coding: utf-8

import os, sys

class Manage:

	argv = []

	name_view = 'index2.phtml'

	controller = '''<?php
	class %s extends Controller
	{
		public function init($params = null)
		{
			
		}

		public function index_action($params = null)
		{
			$this->view('index');
		}
	}'''
	
	model = '''<?php
	class %s_Model extends Model
	{
		public $_tabela = '%s';
	}'''

	view = '%s'

	def __init__(self, argvs):
		self.argv = argvs
		self.name = argvs[argvs.index('-name')+1]
		if '-table' in argvs:
			self.table = argvs[argvs.index('-table')+1]
		else:
			self.table = self.name
		self.define_path()

	def define_path(self):
		os.chdir(os.path.dirname(os.path.abspath(__file__)))
		self.path_model = os.path.abspath('../app/Models/%s_Model.php' %(self.name[0].upper() + self.name[1:]))
		self.path_controller = os.path.abspath('../app/Controllers/%sController.php' %(self.name[0].lower() + self.name[1:]))
		self.path_view = os.path.abspath('../app/Views/%s/index.phtml' %(self.name[0].upper() + self.name[1:]))

	def create_file(self, archive, content):
		with open(archive, 'w') as f:
			f.write(content)
			print "\033[33m%s\033[0;0m\tCriado com sucesso" %f.name
			print "-"*100

	def create_all(self):
		self.create_controller()
		self.create_model()
		self.create_view()

	def create_controller(self):
		self.create_file(self.path_controller, self.controller %(self.name[0].lower() + self.name[1:]))

	def create_view(self):
		pathv = self.path_view.replace('index.phtml', '')
		self.name_view = self.argv[self.argv.index('-v-name') + 1] + ".phtml" if '-v-name' in self.argv else 'index2.phtml'
		if not os.path.exists(pathv):
			os.makedirs(pathv)

		if os.path.exists(self.path_view):
			self.path_view = self.path_view.replace('index.phtml', self.name_view)

		self.create_file(self.path_view, self.view %self.name.capitalize())

	def create_model(self):
		self.create_file(self.path_model, self.model %(self.name.capitalize(), self.table))



if __name__ == '__main__':

	help = '''\n%s\n\033[33mScript que facilita a criação de {Controllers, Views e Models} para o Framework\033[0;0m\n%s\
	\n\n Uso básico: \033[32m manage.py [-name NOME]]\033[0;0m\n
	\n\t-help \t\tExibe o painel de ajuda\
	\n\t-name \t\tPassa o argumento seguido do nome dos arquivos:\033[32m manage.py [-name NOME]\033[0;0m\
	\n\t-table \t\tCaso o nome da tabela no banco de dados seja diferente do passado no argumento -name use esse argumento para passar o nome da tabela:\033[32m manage.py [-name NOME] [-table NOME_TABELA]\033[0;0m\
	\n\t-c \t\tPara criar apenas o controller\033[32m manage.py [-name NOME] [-c]\033[0;0m\
	\n\t-m \t\tPara criar apenas o model:\033[32m manage.py [-name NOME] [-m]\033[0;0m\
	\n\t-v \t\tPara criar apenas a view:\033[32m manage.py [-name NOME] [-v]\033[0;0m\
	\n\t-v-name \tPara escolher um nome específico para o arquivo phtml da view:\033[32m manage.py [-name NOME] [-v-name NOME_VIEW]\033[0;0m (não coloque o '.phtml')\
	\n\n''' %('='*80, '='*80)

	def exists(x):
		return x in ['-c','-v','-m']

	if not '-name' in sys.argv or len(sys.argv[1:]) < 2 or '-help' in sys.argv:
		print help

	else:
		print "\n\n"
		m = Manage(sys.argv)
		if '-c' in sys.argv:
			m.create_controller()
		elif '-v' in sys.argv:
			m.create_view()
		elif '-m' in sys.argv:
			m.create_model()
		else:
			m.create_all()
		print "\n\n"
