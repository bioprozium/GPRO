#pragma once
#include "CommandParser.h"
class Analyzing
{
private:
	//CommandParser::Parameters prm;
	std::string e;
	std::map<std::string,  double> cdnOcc;
	std::map<std::string, std::map<size_t, size_t>> codonPos;
	std::map<size_t, int> seqLength;
	std::map<int, unsigned int> concentration;
	std::map<char, unsigned int> aaContent;
public:
	char aa[64] = {};
	Analyzing(CommandParser::Parameters);			//Construct
	void Analyze(std::string, std::string);			//

	/*-------------------------Calculation-------------------------*/
	void codonOccurrence(std::string);
	void codonPosition(std::string);
	void length(std::string);
	void gcContent(std::string);
	void cpgIsland(std::string);
	void aminoAcidContent(std::string);
	void aminoAcidPosition(std::string);
	void kMers(std::string);
	/*-------------------------Statistics--------------------*/
	void Statistics(std::string);
	void codonOccurrenceStatistics();

	//tools
	void infoBlockInitialize(std::string);
	std::map<std::string,  double> initializeCodonMap(std::map < std::string,  double > );
	void initializeCodonPosMap(std::map<std::string, std::map<size_t, size_t>>&, size_t);
	void initCodonPosindex( size_t);
	void initConcentrationMap(std::map<int,unsigned int>&);
	void initAminoAcidMap(std::map<char,unsigned int>&, char(&)[64]);
	int get_seq_num(const char*, int);
	/*------------------------Results------------------------*/
	void resultPreparation(std::string);
	struct Results
	{
		std::map<size_t, int> sequenceLength;
		std::map<std::string, double> codonOccurence;
		std::map<std::string, std::map<size_t, size_t>> codonPosition;
		std::map<int, unsigned int> gcContentConcentration;
		std::map<int, unsigned int> cpgConcentration;
		std::map<char, unsigned int> aminoAcidCount;
	};
	Results res;
};