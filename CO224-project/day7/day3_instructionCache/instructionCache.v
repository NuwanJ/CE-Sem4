
module main;
   
   	reg CLK;
   	wire busy;
	reg [7:0] PC;
	wire [31:0] instCurrent;
   
    instCache myInstCache(instCurrent, busy, PC, CLK);

    initial begin
    	$dumpfile("out.vcd"); 
		$dumpvars(0, myInstCache);
		CLK<=0;

		// Forma				// 0000_00_00  { tag_index_offset }
        #0
		PC <= 8'b0000_00_00;	// index-0, tag-0, block-0
		
		#200
		PC <= 8'b0001_01_01;	// index-0, tag-0, block-1
		
		#400
		PC <= 8'b0000_10_10; 	// index-1, tag-0, block-0

		#600
		PC <= 8'b0001_11_00; 	// index-0, tag-1, block-0		
		
		#800
		PC <= 8'b0000_00_00; 	// index-0, tag-1, block-0		
		
		#1000
		PC <= 8'b1001_11_00; 	// index-0, tag-1, block-0		
		

		#2000 $finish;
    end
    
    always begin
		#5 CLK=~CLK;
	end

endmodule

module instCache(instCurrent,busy, PC, CLK);
	
	output busy;
	wire busyInst;							// Busy signal from the instruction register
	reg busyCache;							// Busy signal from the cache register
	
	or(busy, busyInst, busyCache);

	input CLK;
	input[7:0] PC;
	
	
	output reg [31:0] instCurrent; 			// Which should return to processor
	
	input [127:0] dataFromInst; 			// 4 word block taking from instruction memory
	output reg readInst;					// signal to instruction memory say, read and give me the data 

	output reg [5:0] instAddress;
	instReg myInst(dataFromInst, busyInst, readInst, instAddress, CLK);

	// Cache special variables
	reg [3:0] TAG [0:3];					// 4 tags of 4-bit
	reg V [0:3];							// 4 valid bits
	//output reg busy;
	
	reg [127:0] cacheData[0:3];				// this contains 4 blocks of (32 bit word x 4)  
	
	wire[3:0] tag;							// 4 bit tag
	wire[1:0] index;						// 2 bit index
	wire[1:0] offset;						// 2 bit offset

	integer i;	

	assign offset = PC[1:0];				// assign the offset from the given address
	assign tag = PC[7:4];					// assign the tag from the given address
	assign index = PC[3:2];					// assign the index from the given address

	initial begin							// initially, reset all the values to zero
		for (i=0;i<4; i=i+1) begin
			cacheData[i] <= 0;
			TAG[i] <= 4'bxxxx;
			V[i] <= 1'b0;
		end
		busyCache <= 1'b0;
		readInst <= 1'b0;

		//$display("\tRESET\n");

	end
	
	always @(PC) begin								// on each clock cycle

		if (TAG[index]==tag && V[index]==1'b1) begin
			// tag match and valid ---> HIT, 			no need any process
			$display("\t HIT  on the [ %b | %b | %b ] V[%b]", tag, index, offset, V[index]);

		end else begin
			// tag isn't match --> Conflict MISS
			$display("\t MISS on the [ %b | %b | %b ] V[%b]\t <loading from the instruction memory>", tag, index, offset, V[index]);

			busyCache <= 1'b1;

			// --- Load -----------------

			readInst <= 1'b1;						// Ask to read
			instAddress <= PC[7:2];					// Data Block address (6 bits)

			#10
			cacheData[index] <= dataFromInst;		// Assign receiver 128bit data to corresponding cache block
			
			// ---------------------------
			
			#2
			readInst <= 1'b0;
			TAG[index] <= tag;	
			V[index] <= 1'b1;	
			busyCache <= 1'b0;

		end

		// Output the requesed data
		if (offset == 0) begin
			instCurrent <= cacheData[index][31:0];
		end else if (offset == 2) begin
			instCurrent <= cacheData[index][63:32];
		end else if (offset == 3) begin
			instCurrent <= cacheData[index][95:64];
		end else begin
			instCurrent <= cacheData[index][127:96];
		end

		//$display("Sending the instruction %b into processor", instCurrent);
		busyCache <= 1'b0;

	end

endmodule

module instReg(instCurrent, busy, read, instAddr, CLK);

    input CLK;						// clock
    input read;						// read and send the data to instruction cache if this is equal to 1
    input [5:0] instAddr;			// the block address [bits:6][offset:2]

    output reg busy;				// send the busy signal to cache when reading the data
    output reg [127:0] instCurrent;	// current data buffer of 4 instructions [bits:128]
    
    reg [255:0] memory [31:0];		// 256 x [4byte] register file
	
    initial begin
    	busy <= 0;

		//	Format	:			  |  OPCODE |  Dest  |  Source2  |  Source1  |
		
		// empty
		memory[0] <= 32'b00000000_00000000_xxxxxxxx_00000000;
		
		//loadi r0,x,#32;
		memory[1] <= 32'b00000000_00000000_00000000_00100000;
		
		// loadi r1,x,#8;
		memory[2] <= 32'b00000000_00000001_00000000_00001000;
		
		// str #2,x,r0;
		memory[3] <= 32'b00000111_00000010_00000000_00000000;
		
		//str #6,x,r1;	
		memory[4] <= 32'b00000111_00000110_00000000_00000001;	

		//load r2,x,#2;
		memory[5]  <= 32'b00000110_00000010_00000000_00000010;
		
		//load r3,x,#6;
		memory[6]  <= 32'b00000110_00000011_00000000_00000110;

		//load r5,x,#4;
		memory[7]  <= 32'b00000110_00000101_00000000_00000100;	
	
    end

	always @(CLK) begin
		if(read == 1'b1) begin
			busy <= 1'b1;

			instCurrent[31:0] <= memory[instAddr];
			instCurrent[63:32] <= memory[instAddr+1];
			instCurrent[95:64] <= memory[instAddr+2];
			instCurrent[127:96] <= memory[instAddr+3];

			$display("\tSent the block %d to the Instruction Cache", instAddr);
			#1000 busy <= 1'b0;
		end
	end
endmodule
